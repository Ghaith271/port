<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_form_is_available_to_guests(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('password');
    }

    public function test_valid_credentials_authenticate_and_logout_clears_authentication(): void
    {
        $user = User::factory()->create(['password' => Hash::make('A-secure-test-password!')]);

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'A-secure-test-password!'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invalid_credentials_do_not_authenticate(): void
    {
        $user = User::factory()->create();
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'wrong-password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_repeated_failed_login_blocks_even_correct_credentials_until_timeout(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.test', 'password' => Hash::make('Correct-password!')]);
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.store'), ['email' => $user->email, 'password' => 'incorrect'])
                ->assertSessionHasErrors('email');
        }
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'Correct-password!'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertTrue(RateLimiter::tooManyAttempts('login:admin@example.test|127.0.0.1', 5));

        $this->travel(61)->seconds();
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'Correct-password!'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_public_registration_is_not_available(): void
    {
        $this->post('/register', ['name' => 'Intruder', 'email' => 'intruder@example.test', 'password' => 'password'])
            ->assertNotFound();
        $this->assertDatabaseCount('users', 0);
    }
}
