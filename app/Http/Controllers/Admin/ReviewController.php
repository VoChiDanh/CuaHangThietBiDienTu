<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $reviewsQuery = Review::with(['user', 'product'])
            ->latest();

        if ($status && in_array($status, ['approved', 'pending', 'hidden'])) {
            $reviewsQuery->where('status', $status);
        }

        $reviews = $reviewsQuery->paginate(15)->withQueryString();

        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('status', 'approved')->count(),
            'pending' => Review::where('status', 'pending')->count(),
            'hidden' => Review::where('status', 'hidden')->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats', 'status'));
    }

    public function updateStatus(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,pending,hidden'],
        ]);

        $review->update(['status' => $validated['status']]);

        return back()->with('success', "Đã chuyển trạng thái đánh giá thành '{$validated['status']}'!");
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'admin_reply' => ['required', 'string', 'max:2000'],
        ], [
            'admin_reply.required' => 'Vui lòng nhập nội dung phản hồi từ Shop.',
        ]);

        $review->update([
            'admin_reply' => $validated['admin_reply'],
        ]);

        return back()->with('success', 'Đã lưu câu trả lời của Shop cho đánh giá này!');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá thành công.');
    }
}
