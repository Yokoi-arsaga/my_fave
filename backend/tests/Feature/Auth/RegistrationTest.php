<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'passWORD123',
            'password_confirmation' => 'passWORD123',
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $response->id, 'hash' => sha1($response->email)]
        );

        $this->get($verificationUrl);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /**
     * メールアドレスが承認されておらず、ログインできない場合
     *
     * @return void
     */
    public function test_new_users_can_not_register_with_not_verified()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'passWORD123',
            'password_confirmation' => 'passWORD123',
        ]);

        $this->assertGuest();
    }

    /**
     * メールアドレスが不正で登録できない場合
     *
     * @return void
     */
    public function test_new_users_can_not_register_by_email()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'passWORD123',
            'password_confirmation' => 'passWORD123',
        ]);

        $this->assertGuest();
    }

    /**
     * メールアドレスが重複して登録できない場合
     *
     * @return void
     */
    public function test_new_users_can_not_register_by_email_duplicate()
    {
        $user = User::factory()->create();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => $user->email,
            'password' => 'passWORD123',
            'password_confirmation' => 'passWORD123',
        ]);

        $this->assertGuest();
    }

    /**
     * パスワードが不正で登録できなかった場合
     * 数字が入っていない
     *
     * @return void
     */
    public function test_new_users_can_not_register_by_password_not_digits()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'passWORDD',
            'password_confirmation' => 'passWORDD',
        ]);

        $this->assertGuest();
    }

    /**
     * パスワードが不正で登録できなかった場合
     * パスワードがあっていない
     *
     * @return void
     */
    public function test_new_users_can_not_register_by_password_not_fit()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'passWORD123',
            'password_confirmation' => 'passWORD122',
        ]);

        $this->assertGuest();
    }

    /**
     * パスワードが不正で登録できなかった場合
     * 大文字と小文字が混ぜられていない
     *
     * @return void
     */
    public function test_new_users_can_not_register_by_password_not_mix()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertGuest();
    }
}
