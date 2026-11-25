<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Gửi đánh giá cho sản phẩm (Yêu cầu Verified Purchase)
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:5', 'max:2000'],
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá (1 - 5 sao).',
            'comment.required' => 'Vui lòng nhập nhận xét chi tiết của bạn.',
            'comment.min' => 'Nhận xét phải có ít nhất 5 ký tự.',
        ]);

        $userId = auth()->id();
        $productId = (int) $validated['product_id'];

        // Kiểm tra ràng buộc Verified Purchase:
        // Khách hàng phải có đơn hàng hoàn thành (completed) chứa sản phẩm này
        $orderItem = OrderItem::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->whereIn('status', ['completed', 'delivered']);
        })->whereHas('variant', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->whereDoesntHave('review') // Chưa từng đánh giá dòng hàng này
            ->first();

        if (! $orderItem) {
            return back()->with('error', 'Chỉ khách hàng đã mua và nhận hàng thành công mới được viết đánh giá cho sản phẩm này.');
        }

        // Bộ lọc từ ngữ không phù hợp cơ bản
        $badWords = ['lừa đảo', 'đm', 'vcl', 'clgt', 'dm'];
        $cleanComment = $validated['comment'];
        foreach ($badWords as $word) {
            $cleanComment = preg_replace("/\b".preg_quote($word, '/')."\b/iu", '***', $cleanComment);
        }

        Review::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'order_item_id' => $orderItem->id,
            'rating' => $validated['rating'],
            'comment' => $cleanComment,
            'status' => 'approved', // Tự động duyệt hoặc chuyển sang pending nếu cần
        ]);

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá! Đánh giá của bạn giúp cộng đồng chọn được sản phẩm tốt hơn.');
    }
}
