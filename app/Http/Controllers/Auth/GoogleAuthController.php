<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Đăng nhập Google không thành công hoặc bạn đã hủy thao tác.');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Cập nhật google_id và avatar nếu chưa có
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar() ?: $user->avatar,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        } else {
            // Tạo mới người dùng từ thông tin Google
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Khách hàng SmartTech',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(24)),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);

        if ($user->isAdmin() || $user->isStaff()) {
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công với tài khoản Google Quản trị!');
        }

        return redirect('/')->with('success', "Xin chào {$user->name}, bạn đã đăng nhập thành công qua Google!");
    }
}
