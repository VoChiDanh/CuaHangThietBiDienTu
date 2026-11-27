<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SpecificationGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductManagementController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['brand', 'category'])
            ->withCount('variants')
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $search = $request->string('search')->trim()->toString();
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku_master', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('brand_id'), fn (Builder $query) => $query->where('brand_id', $request->integer('brand_id')))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(['is_active' => true, 'warranty_months' => 12]),
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'specificationGroups' => $this->specificationGroups(),
            'formVariants' => collect([new ProductVariant(['price' => 0, 'stock_quantity' => 0, 'low_stock_threshold' => 5])]),
            'existingImages' => collect(),
        ]);
    }

    public function edit(Product $product): View
    {
        $product->load(['variants.images', 'productSpecifications']);

        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'specificationGroups' => $this->specificationGroups(),
            'formVariants' => $product->variants->isNotEmpty()
                ? $product->variants
                : collect([new ProductVariant(['price' => $product->base_price, 'stock_quantity' => 0, 'low_stock_threshold' => 5])]),
            'existingImages' => $product->images()->get()->groupBy('variant_id'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        $product = DB::transaction(function () use ($request, $validated): Product {
            $product = new Product;
            $this->fillProduct($product, $request, $validated);
            $product->save();
            $this->syncVariants($product, $request, $validated['variants']);
            $this->syncSpecifications($product, $request, $validated['specifications'] ?? []);

            return $product;
        });

        return redirect()->route('admin.products.edit', $product)
            ->with('success', "Đã tạo sản phẩm [{$product->name}] thành công.");
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product);
        $submittedVariantIds = collect($validated['variants'])->pluck('id')->filter()->map(fn ($id): int => (int) $id);
        $removedVariantIds = $product->variants()->whereNotIn('id', $submittedVariantIds)->pluck('id');

        if ($removedVariantIds->isNotEmpty() && DB::table('order_items')->whereIn('product_variant_id', $removedVariantIds)->exists()) {
            return back()->withInput()->with('error', 'Không thể xóa biến thể đã có trong đơn hàng. Hãy giữ biến thể và tắt trạng thái bán.');
        }

        DB::transaction(function () use ($request, $product, $validated): void {
            $this->fillProduct($product, $request, $validated);
            $product->save();
            $this->syncVariants($product, $request, $validated['variants']);
            $this->syncSpecifications($product, $request, $validated['specifications'] ?? []);
        });

        return redirect()->route('admin.products.edit', $product)
            ->with('success', "Đã cập nhật sản phẩm [{$product->name}] thành công.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $hasOrders = DB::table('order_items')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->where('product_variants.product_id', $product->id)
            ->exists();

        if ($hasOrders) {
            return back()->with('error', 'Sản phẩm đã xuất hiện trong đơn hàng nên không thể xóa. Hãy tắt trạng thái hiển thị thay thế.');
        }

        $productName = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Đã xóa sản phẩm [{$productName}].");
    }

    /** @return array<string, mixed> */
    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $productId = $product?->id;
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId)],
            'sku_master' => ['required', 'string', 'max:100', Rule::unique('products', 'sku_master')->ignore($productId)],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:base_price'],
            'warranty_months' => ['required', 'integer', 'min:0', 'max:600'],
            'weight_grams' => ['nullable', 'integer', 'min:0'],
            'battery_mah' => ['nullable', 'integer', 'min:0'],
            'battery_wh' => ['nullable', 'numeric', 'min:0'],
            'screen_size' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'ai_summary_json' => ['nullable', 'json'],
            'total_sold' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.id' => $product
                ? ['nullable', 'integer', Rule::exists('product_variants', 'id')->where('product_id', $product->id)]
                : ['nullable', 'prohibited'],
            'variants.*.sku' => ['required', 'string', 'max:100'],
            'variants.*.color_name' => ['nullable', 'string', 'max:100'],
            'variants.*.color_hex' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'variants.*.ram' => ['nullable', 'string', 'max:50'],
            'variants.*.storage' => ['nullable', 'string', 'max:50'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
            'variants.*.sale_price' => ['nullable', 'numeric', 'min:0', 'lte:variants.*.price'],
            'variants.*.stock_quantity' => ['required', 'integer', 'min:0'],
            'variants.*.low_stock_threshold' => ['required', 'integer', 'min:0'],
            'variants.*.is_active' => ['nullable', 'boolean'],
            'variants.*.remove_images.*' => ['nullable', 'boolean'],
            'variants.*.images.main' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'variants.*.images.back' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'variants.*.images.ports' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'specifications' => ['nullable', 'array'],
            'specifications.*' => ['nullable', 'string', 'max:255'],
        ];

        $variantInput = $request->input('variants', []);
        foreach (is_array($variantInput) ? $variantInput : [] as $index => $variantData) {
            if (! is_array($variantData)) {
                continue;
            }

            $rules["variants.{$index}.sku"] = [
                'required',
                'string',
                'max:100',
                Rule::unique('product_variants', 'sku')->ignore($variantData['id'] ?? null),
            ];
        }

        return $request->validate($rules, [
            'sale_price.lte' => 'Giá khuyến mãi của sản phẩm không được cao hơn giá gốc.',
            'variants.*.color_hex.regex' => 'Mã màu phải có định dạng #RRGGBB.',
            'variants.*.images.*.max' => 'Mỗi ảnh không được vượt quá 5 MB.',
        ]);
    }

    /** @param array<string, mixed> $validated */
    private function fillProduct(Product $product, Request $request, array $validated): void
    {
        $slug = trim((string) ($validated['slug'] ?? ''));
        if ($slug === '') {
            $slug = Str::slug($validated['name']);
            $baseSlug = $slug;
            $suffix = 2;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id ?? 0)->exists()) {
                $slug = "{$baseSlug}-{$suffix}";
                $suffix++;
            }
        }

        $summary = isset($validated['ai_summary_json']) && $validated['ai_summary_json'] !== ''
            ? json_decode($validated['ai_summary_json'], true, 512, JSON_THROW_ON_ERROR)
            : null;

        $product->fill([
            'name' => $validated['name'],
            'slug' => $slug,
            'sku_master' => $validated['sku_master'],
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'base_price' => $validated['base_price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'warranty_months' => $validated['warranty_months'],
            'weight_grams' => $validated['weight_grams'] ?? null,
            'battery_mah' => $validated['battery_mah'] ?? null,
            'battery_wh' => $validated['battery_wh'] ?? null,
            'screen_size' => $validated['screen_size'] ?? null,
            'description' => $validated['description'] ?? null,
            'ai_summary' => $summary,
            'total_sold' => $validated['total_sold'],
            'is_active' => $request->boolean('is_active'),
        ]);
    }

    /** @param array<int, array<string, mixed>> $variants */
    private function syncVariants(Product $product, Request $request, array $variants): void
    {
        $existingVariants = $product->variants()->get()->keyBy('id');
        $submittedIds = collect($variants)->pluck('id')->filter()->map(fn ($id): int => (int) $id);
        $removedVariants = $existingVariants->whereNotIn('id', $submittedIds);

        if ($removedVariants->isNotEmpty()) {
            $lockedVariantId = DB::table('order_items')
                ->whereIn('product_variant_id', $removedVariants->keys())
                ->value('product_variant_id');

            if ($lockedVariantId !== null) {
                throw ValidationException::withMessages([
                    'variants' => 'Không thể xóa biến thể đã có trong đơn hàng. Hãy giữ biến thể và tắt trạng thái bán.',
                ]);
            }

            $product->images()->whereIn('variant_id', $removedVariants->keys())->delete();
            $product->variants()->whereIn('id', $removedVariants->keys())->delete();
        }

        foreach ($variants as $index => $variantData) {
            $variantId = $variantData['id'] ?? null;
            if ($variantId !== null && ! $existingVariants->has((int) $variantId)) {
                throw ValidationException::withMessages([
                    'variants' => 'Biến thể gửi lên không thuộc sản phẩm đang chỉnh sửa.',
                ]);
            }

            $variant = $variantId
                ? $existingVariants->get((int) $variantId)
                : new ProductVariant;

            $variant->fill([
                'sku' => $variantData['sku'],
                'color_name' => $variantData['color_name'] ?? null,
                'color_hex' => $variantData['color_hex'] ?? null,
                'ram' => $variantData['ram'] ?? null,
                'storage' => $variantData['storage'] ?? null,
                'price' => $variantData['price'],
                'sale_price' => $variantData['sale_price'] ?? null,
                'stock_quantity' => $variantData['stock_quantity'],
                'low_stock_threshold' => $variantData['low_stock_threshold'],
                'is_active' => $request->boolean("variants.{$index}.is_active"),
            ]);
            $product->variants()->save($variant);

            foreach (['main', 'back', 'ports'] as $sortOrder => $angle) {
                $file = $request->file("variants.{$index}.images.{$angle}");
                if ($file === null) {
                    if ($request->boolean("variants.{$index}.remove_images.{$angle}")) {
                        $variant->images()->where('angle_type', $angle)->delete();
                    }

                    continue;
                }

                $image = $variant->images()->firstOrNew(['angle_type' => $angle]);
                $image->fill([
                    'product_id' => $product->id,
                    'image_url' => '/storage/'.$file->store('products', 'public'),
                    'sort_order' => $sortOrder + 1,
                ]);
                $image->save();
            }
        }
    }

    /** @param array<int, string|null> $specifications */
    private function syncSpecifications(Product $product, Request $request, array $specifications): void
    {
        $validSpecificationIds = SpecificationGroup::query()
            ->where('category_id', $product->category_id)
            ->with('specifications')
            ->get()
            ->flatMap(fn (SpecificationGroup $group) => $group->specifications->pluck('id'))
            ->map(fn ($id): int => (int) $id)
            ->all();

        $product->productSpecifications()->delete();
        foreach ($specifications as $specificationId => $value) {
            if (! in_array((int) $specificationId, $validSpecificationIds, true) || trim((string) $value) === '') {
                continue;
            }

            $value = trim((string) $value);
            $product->productSpecifications()->create([
                'specification_id' => (int) $specificationId,
                'value_text' => $value,
                'value_numeric' => is_numeric($value) ? $value : null,
            ]);
        }
    }

    private function specificationGroups(): Collection
    {
        return SpecificationGroup::query()
            ->with(['category', 'specifications'])
            ->orderBy('sort_order')
            ->get();
    }
}
