<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permissionSlug): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $user = auth()->user();

        // Admin luôn có toàn quyền
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Nhân viên kiểm tra quyền hạn cụ thể
        if ($user->isStaff() && $user->hasPermission($permissionSlug)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không được phân quyền thực hiện chức năng này.',
            ], 403);
        }

        abort(403, "Bạn không có quyền '{$permissionSlug}' để truy cập chức năng này.");
    }
}
