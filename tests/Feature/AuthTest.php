<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Đăng nhập');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::where('email', 'admin@techstore.vn')->first();
        if ($user) {
            $user->update(['password' => Hash::make('12345678')]);
        } else {
            $user = User::create([
                'name' => 'Admin Test',
                'email' => 'admin@techstore.vn',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'is_active' => true,
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'admin@techstore.vn',
            'password' => '12345678',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@techstore.vn',
            'password' => 'wrong-password-999',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_new_users_can_register(): void
    {
        $email = 'newcustomer_'.time().'@gmail.com';
        $response = $this->post('/register', [
            'name' => 'Nguyễn Khách Hàng',
            'email' => $email,
            'phone' => '0988776655',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => $email, 'role' => 'customer']);
        $response->assertRedirect('/');
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('role', 'customer')->first();
        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
