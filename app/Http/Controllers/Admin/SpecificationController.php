<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductSpecification;
use App\Models\Specification;
use App\Models\SpecificationGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SpecificationController extends Controller
{
    public function index(): View
    {
        return view('admin.specifications.index', [
            'categories' => Category::orderBy('name')->get(),
            'groups' => SpecificationGroup::with(['category', 'specifications'])
                ->orderBy('sort_order')->get(),
        ]);
    }

    public function storeGroup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        SpecificationGroup::create($validated);

        return back()->with('success', 'Đã thêm nhóm thông số.');
    }

    public function updateGroup(Request $request, SpecificationGroup $group): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $group->update($validated);

        return back()->with('success', 'Đã cập nhật nhóm thông số.');
    }

    public function destroyGroup(SpecificationGroup $group): RedirectResponse
    {
        $specificationIds = $group->specifications()->pluck('id');
        if (ProductSpecification::whereIn('specification_id', $specificationIds)->exists()) {
            return back()->with('error', 'Không thể xóa nhóm đang được sản phẩm sử dụng. Hãy gỡ các giá trị thông số khỏi sản phẩm trước.');
        }

        $group->delete();

        return back()->with('success', 'Đã xóa nhóm thông số.');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSpecification($request);
        Specification::create($validated);

        return back()->with('success', 'Đã thêm thông số kỹ thuật.');
    }

    public function update(Request $request, Specification $specification): RedirectResponse
    {
        $validated = $this->validateSpecification($request);
        $specification->update($validated);

        return back()->with('success', 'Đã cập nhật thông số kỹ thuật.');
    }

    public function destroy(Specification $specification): RedirectResponse
    {
        if ($specification->productSpecifications()->exists()) {
            return back()->with('error', 'Thông số đang được sản phẩm sử dụng. Hãy xóa giá trị đó khỏi sản phẩm trước.');
        }

        $specification->delete();

        return back()->with('success', 'Đã xóa thông số kỹ thuật.');
    }

    /** @return array<string, mixed> */
    private function validateSpecification(Request $request): array
    {
        return $request->validate([
            'group_id' => ['required', 'integer', 'exists:specification_groups,id'],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:50'],
            'compare_rule' => ['required', Rule::in(['none', 'higher_is_better', 'lower_is_better'])],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }
}
