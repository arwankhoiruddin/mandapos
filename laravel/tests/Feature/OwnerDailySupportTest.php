<?php

namespace Tests\Feature;

use App\Models\Restoran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerDailySupportTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_sees_daily_support_form_on_first_login_of_day(): void
    {
        $user = User::factory()->create();
        $restoran = Restoran::create(['nama' => 'Warung Berkah']);
        $user->restorans()->attach($restoran->id, ['role' => 'owner']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Kamu tetap bisa menggunakan POS ini tanpa biaya.');
    }

    public function test_owner_form_disappears_after_submitting_support_for_today(): void
    {
        $user = User::factory()->create();
        $restoran = Restoran::create(['nama' => 'Warung Berkah']);
        $user->restorans()->attach($restoran->id, ['role' => 'owner']);

        $this->actingAs($user)->post('/dukungan-harian', [
            'nominal' => 20000,
        ])->assertRedirect(route('dashboard', absolute: false));

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertDontSee('Kamu tetap bisa menggunakan POS ini tanpa biaya.');
    }

    public function test_non_owner_cannot_submit_daily_support(): void
    {
        $user = User::factory()->create();
        $restoran = Restoran::create(['nama' => 'Warung Berkah']);
        $user->restorans()->attach($restoran->id, ['role' => 'kasir']);

        $this->actingAs($user)
            ->post('/dukungan-harian', ['nominal' => 20000])
            ->assertForbidden();
    }
}
