<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        return view('admin.brands.index', [
            'brands' => Brand::withCount('products')->orderBy('name')->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateBrand($request);
        $brand = Brand::create($this->brandData($request, $validated));

        return redirect()->route('admin.brands.index')->with('success', "Đã thêm thương hiệu [{$brand->name}].");
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $this->validateBrand($request, $brand);
        $brand->update($this->brandData($request, $validated, $brand));

        return redirect()->route('admin.brands.index')->with('success', "Đã cập nhật thương hiệu [{$brand->name}].");
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->products()->exists()) {
            return back()->with('error', 'Không thể xóa thương hiệu đang được sản phẩm sử dụng. Hãy chuyển sản phẩm sang thương hiệu khác trước.');
        }

        $brandName = $brand->name;
        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', "Đã xóa thương hiệu [{$brandName}].");
    }

    /** @return array<string, mixed> */
    private function validateBrand(Request $request, ?Brand $brand = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('brands', 'slug')->ignore($brand?->id)],
            'logo_url' => ['nullable', 'string', 'max:2048'],
            'is_visible' => ['nullable', 'boolean'],
        ]);
    }

    /** @param array<string, mixed> $validated
     * @return array<string, mixed>
     */
    private function brandData(Request $request, array $validated, ?Brand $brand = null): array
    {
        $slug = trim((string) ($validated['slug'] ?? ''));
        if ($slug === '') {
            $slug = Str::slug($validated['name']);
            $baseSlug = $slug;
            $suffix = 2;
            while (Brand::where('slug', $slug)->where('id', '!=', $brand?->id ?? 0)->exists()) {
                $slug = "{$baseSlug}-{$suffix}";
                $suffix++;
            }
        }

        return [
            'name' => $validated['name'],
            'slug' => $slug,
            'logo_url' => $validated['logo_url'] ?? null,
            'is_visible' => $request->boolean('is_visible'),
        ];
    }
}
