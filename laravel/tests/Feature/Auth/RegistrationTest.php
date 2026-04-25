<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'nama_restoran' => 'Warung Uji Coba',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'test@example.com')->firstOrFail();
        $this->assertTrue($user->isOwner());
        $this->assertCount(1, $user->restorans);
    }

    public function test_new_owner_sees_daily_support_form_after_registering(): void
    {
        $response = $this->followingRedirects()->post('/register', [
            'name' => 'Test User',
            'email' => 'ownerbaru@example.com',
            'nama_restoran' => 'Warung Baru',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertStatus(200);
        $response->assertSee('Kamu tetap bisa menggunakan POS ini tanpa biaya.');
    }
}
