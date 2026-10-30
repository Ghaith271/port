<?php

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesPortfolio;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use CreatesPortfolio, RefreshDatabase;

    public function test_public_portfolio_contains_published_projects_and_hides_drafts(): void
    {
        $this->createProfile();
        $this->createProject(['title' => 'Visible work']);
        $this->createProject(['title' => 'Private draft', 'slug' => 'private-draft', 'published' => false]);

        $this->get(route('home'))->assertOk()
            ->assertSee('غيث')->assertSee('Visible work')->assertDontSee('Private draft');
    }

    public function test_public_content_is_escaped_instead_of_executed(): void
    {
        $payload = '<script>alert("portfolio")</script>';
        $this->createProfile(['name' => $payload]);
        $this->createProject(['title' => $payload]);

        $this->get(route('home'))->assertOk()
            ->assertSee($payload)->assertDontSee($payload, false);
    }

    public function test_valid_contact_message_is_persisted_and_redirects_to_contact_section(): void
    {
        $this->post(route('contact.store'), $this->contactData())
            ->assertRedirect(route('home').'#contact')->assertSessionHas('success');

        $this->assertDatabaseHas('messages', ['email' => 'visitor@example.test', 'read_at' => null]);
        $this->assertDatabaseCount('messages', 1);
    }

    public function test_contact_form_rejects_invalid_fields_and_does_not_store_them(): void
    {
        $this->post(route('contact.store'), [
            'name' => '', 'email' => 'not-an-email', 'subject' => '', 'message' => 'short',
        ])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_honeypot_rejects_bot_submission(): void
    {
        $this->post(route('contact.store'), $this->contactData(['website' => 'https://spam.test']))
            ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_contact_endpoint_limits_repeated_submissions(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('contact.store'), $this->contactData())->assertRedirect();
        }
        $this->post(route('contact.store'), $this->contactData())->assertStatus(429);
        $this->assertSame(5, Message::count());
    }

    public function test_contact_does_not_allow_setting_read_status(): void
    {
        $this->post(route('contact.store'), $this->contactData(['read_at' => now()->toISOString()]))
            ->assertRedirect();
        $this->assertNull(Message::firstOrFail()->read_at);
    }

    private function contactData(array $attributes = []): array
    {
        return array_merge([
            'name' => 'A visitor', 'email' => 'visitor@example.test',
            'subject' => 'A new project', 'message' => 'I would like to discuss a new website project.',
            'website' => '',
        ], $attributes);
    }
}
