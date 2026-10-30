<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdministrationCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_creates_administrator_with_hashed_password(): void
    {
        $password = 'A-long-admin-password!';
        $this->artisan('portfolio:admin', ['--email' => 'owner@example.test', '--name' => 'Portfolio owner'])
            ->expectsQuestion('Password (at least 12 characters)', $password)
            ->expectsQuestion('Confirm password', $password)
            ->expectsOutput('Administrator created. Sign in at /login.')
            ->assertSuccessful();

        $user = User::where('email', 'owner@example.test')->firstOrFail();
        $this->assertSame('Portfolio owner', $user->name);
        $this->assertNotSame($password, $user->getRawOriginal('password'));
        $this->assertTrue(Hash::check($password, $user->password));
    }

    public function test_command_rejects_duplicate_email_without_modifying_existing_account(): void
    {
        $existing = User::factory()->create(['email' => 'owner@example.test']);
        $hash = $existing->getRawOriginal('password');
        $this->artisan('portfolio:admin', ['--email' => 'owner@example.test', '--name' => 'New owner'])
            ->expectsQuestion('Password (at least 12 characters)', 'A-long-admin-password!')
            ->expectsQuestion('Confirm password', 'A-long-admin-password!')
            ->assertFailed();
        $this->assertDatabaseCount('users', 1);
        $this->assertSame($hash, $existing->fresh()->getRawOriginal('password'));
    }

    public function test_command_rejects_password_shorter_than_twelve_characters(): void
    {
        $this->artisan('portfolio:admin', ['--email' => 'owner@example.test', '--name' => 'Owner'])
            ->expectsQuestion('Password (at least 12 characters)', 'short-pass')
            ->expectsQuestion('Confirm password', 'short-pass')
            ->assertFailed();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_command_rejects_mismatched_password_confirmation(): void
    {
        $this->artisan('portfolio:admin', ['--email' => 'owner@example.test', '--name' => 'Owner'])
            ->expectsQuestion('Password (at least 12 characters)', 'A-long-admin-password!')
            ->expectsQuestion('Confirm password', 'A-different-password!')
            ->assertFailed();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_command_rejects_invalid_email_address(): void
    {
        $this->artisan('portfolio:admin', ['--email' => 'invalid-address', '--name' => 'Owner'])
            ->expectsQuestion('Password (at least 12 characters)', 'A-long-admin-password!')
            ->expectsQuestion('Confirm password', 'A-long-admin-password!')
            ->assertFailed();
        $this->assertDatabaseCount('users', 0);
    }
}
