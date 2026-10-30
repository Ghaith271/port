<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesPortfolio;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use CreatesPortfolio, RefreshDatabase;

    public function test_every_admin_screen_requires_authentication(): void
    {
        foreach (['admin.dashboard', 'admin.profile.edit', 'admin.projects.index', 'admin.projects.create', 'admin.skills.index', 'admin.messages.index'] as $route) {
            $this->get(route($route))->assertRedirect(route('login'));
        }
    }

    public function test_guests_cannot_write_or_delete_admin_content(): void
    {
        $project = $this->createProject();
        $skill = Skill::create(['name' => 'PHP', 'category' => 'Backend', 'level' => 90]);
        $message = Message::create(['name' => 'Visitor', 'email' => 'visitor@example.test', 'subject' => 'Hello', 'message' => 'A project inquiry.']);
        $this->post(route('admin.projects.store'), $this->projectData())->assertRedirect(route('login'));
        $this->put(route('admin.projects.update', $project), $this->projectData(['title' => 'Changed']))->assertRedirect(route('login'));
        $this->delete(route('admin.projects.destroy', $project))->assertRedirect(route('login'));
        $this->post(route('admin.skills.store'), ['name' => 'SQL', 'category' => 'Data', 'level' => 90])->assertRedirect(route('login'));
        $this->delete(route('admin.skills.destroy', $skill))->assertRedirect(route('login'));
        $this->put(route('admin.profile.update'), ['name' => 'Changed'])->assertRedirect(route('login'));
        $this->get(route('admin.messages.show', $message))->assertRedirect(route('login'));
        $this->delete(route('admin.messages.destroy', $message))->assertRedirect(route('login'));
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'title' => 'Customer portal']);
        $this->assertDatabaseHas('skills', ['id' => $skill->id]);
        $this->assertDatabaseHas('messages', ['id' => $message->id, 'read_at' => null]);
    }

    public function test_authenticated_admin_screens_render_successfully(): void
    {
        $this->createProfile();
        $project = $this->createProject();
        $this->actingAs(User::factory()->create());
        foreach (['admin.dashboard', 'admin.profile.edit', 'admin.projects.index', 'admin.projects.create', 'admin.skills.index', 'admin.messages.index'] as $route) {
            $this->get(route($route))->assertOk();
        }
        $this->get(route('admin.projects.edit', $project))->assertOk();
    }

    public function test_admin_can_create_update_and_delete_project_and_technology_tags_are_parsed(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('admin.projects.store'), $this->projectData())
            ->assertRedirect(route('admin.projects.index'))->assertSessionHas('success');
        $project = Project::firstOrFail();
        $this->assertSame(['Laravel', 'JavaScript', 'Bootstrap'], $project->tech_stack);
        $this->assertTrue($project->published);

        $data = $this->projectData(['title' => 'Updated project', 'tech_stack' => 'PHP, SQL']);
        unset($data['featured'], $data['published']);
        $this->put(route('admin.projects.update', $project), $data)->assertRedirect(route('admin.projects.index'));
        $project->refresh();
        $this->assertSame('Updated project', $project->title);
        $this->assertSame(['PHP', 'SQL'], $project->tech_stack);
        $this->assertFalse($project->published);
        $this->assertFalse($project->featured);

        $this->delete(route('admin.projects.destroy', $project))->assertRedirect();
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_project_requires_unique_slug_and_safe_urls_and_accent_color(): void
    {
        $this->createProject();
        $this->actingAs(User::factory()->create());
        $this->post(route('admin.projects.store'), $this->projectData([
            'project_url' => 'javascript:alert(1)', 'github_url' => 'data:text/html,unsafe', 'accent_color' => 'red;display:none',
        ]))->assertSessionHasErrors(['slug', 'project_url', 'github_url', 'accent_color']);
        $this->assertDatabaseCount('projects', 1);
    }

    public function test_empty_slug_is_generated_and_is_preserved_when_editing(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('admin.projects.store'), $this->projectData(['slug' => '']))->assertRedirect();
        $project = Project::firstOrFail();
        $slug = $project->slug;
        $this->assertNotEmpty($slug);
        $this->put(route('admin.projects.update', $project), $this->projectData(['slug' => '']))->assertRedirect();
        $this->assertSame($slug, $project->fresh()->slug);
    }

    public function test_admin_can_create_update_and_delete_skill_with_level_validation(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('admin.skills.store'), ['name' => 'Laravel', 'category' => 'Backend', 'level' => 95])->assertRedirect();
        $skill = Skill::firstOrFail();
        $this->assertSame(0, $skill->sort_order);
        $this->put(route('admin.skills.update', $skill), ['name' => 'Laravel', 'category' => 'Backend', 'level' => 101])->assertSessionHasErrors('level');
        $this->assertEquals(95, $skill->fresh()->level);
        $this->put(route('admin.skills.update', $skill), ['name' => 'Laravel 12', 'category' => 'Backend', 'level' => 90, 'sort_order' => 3])->assertRedirect();
        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name' => 'Laravel 12', 'level' => 90, 'sort_order' => 3]);
        $this->delete(route('admin.skills.destroy', $skill))->assertRedirect();
        $this->assertDatabaseCount('skills', 0);
    }

    public function test_profile_can_be_updated_and_unsafe_social_urls_are_rejected(): void
    {
        $profile = $this->createProfile();
        $this->actingAs(User::factory()->create());
        $data = $profile->only(['name', 'title', 'tagline', 'bio', 'email', 'location']);
        $data['name'] = 'New name';
        $data['github_url'] = 'https://github.com/example';
        $this->put(route('admin.profile.update'), $data)->assertRedirect()->assertSessionHas('success');
        $this->assertSame('New name', $profile->fresh()->name);
        $this->assertFalse($profile->fresh()->available);
        $this->put(route('admin.profile.update'), array_merge($data, ['github_url' => 'javascript:alert(1)']))->assertSessionHasErrors('github_url');
        $this->assertSame('https://github.com/example', $profile->fresh()->github_url);
    }

    public function test_opening_message_marks_it_read_preserves_timestamp_and_escapes_body(): void
    {
        $this->actingAs(User::factory()->create());
        $payload = '<script>alert("message")</script>';
        $message = Message::create(['name' => 'Visitor', 'email' => 'visitor@example.test', 'subject' => 'Hello', 'message' => $payload]);
        $this->get(route('admin.messages.show', $message))->assertOk()->assertSee($payload)->assertDontSee($payload, false);
        $readAt = $message->fresh()->read_at;
        $this->assertNotNull($readAt);
        $this->travel(5)->minutes();
        $this->get(route('admin.messages.show', $message))->assertOk();
        $this->assertEquals($readAt, $message->fresh()->read_at);
        $this->delete(route('admin.messages.destroy', $message))->assertRedirect(route('admin.messages.index'));
        $this->assertDatabaseCount('messages', 0);
    }

    public function test_profile_instagram_and_phone_are_saved_and_unsafe_contact_values_are_rejected(): void
    {
        $profile = $this->createProfile();
        $this->actingAs(User::factory()->create());
        $data = $profile->only(['name', 'title', 'tagline', 'bio', 'email', 'location']);
        $data['instagram_url'] = 'https://www.instagram.com/ghaith.m717/';
        $data['phone'] = '+964 770 123 4567';

        $this->put(route('admin.profile.update'), $data)->assertRedirect()->assertSessionHas('success');
        $this->assertSame($data['instagram_url'], $profile->fresh()->instagram_url);
        $this->assertSame($data['phone'], $profile->fresh()->phone);

        $this->put(route('admin.profile.update'), array_merge($data, [
            'instagram_url' => 'javascript:alert(1)', 'phone' => '<script>alert(1)</script>',
        ]))->assertSessionHasErrors(['instagram_url', 'phone']);
        $this->assertSame($data['instagram_url'], $profile->fresh()->instagram_url);
        $this->assertSame($data['phone'], $profile->fresh()->phone);
    }
}
