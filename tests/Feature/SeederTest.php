<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_initial_seed_creates_public_demo_content_without_admin_credentials(): void
    {
        $this->seed();
        $this->assertDatabaseCount('profiles', 1);
        $this->assertGreaterThan(0, Project::where('published', true)->count());
        $this->assertGreaterThan(0, Skill::count());
        $this->assertSame(0, User::count());
        $this->get(route('home'))->assertOk();
    }

    public function test_reseeding_does_not_overwrite_edits_duplicate_content_or_resurrect_deleted_demos(): void
    {
        $this->seed();
        $profile = Profile::firstOrFail();
        $profile->update(['name' => 'Personalized owner', 'bio' => 'My own edited biography.']);
        $project = Project::firstOrFail();
        $project->update(['title' => 'My updated project']);
        $deletedProject = Project::whereKeyNot($project->id)->firstOrFail();
        $deletedProject->delete();
        $skill = Skill::firstOrFail();
        $skill->update(['level' => 42]);
        $deletedSkill = Skill::whereKeyNot($skill->id)->firstOrFail();
        $deletedSkill->delete();
        $projectCount = Project::count();
        $skillCount = Skill::count();

        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('profiles', 1);
        $this->assertSame($projectCount, Project::count());
        $this->assertSame($skillCount, Skill::count());
        $this->assertSame('Personalized owner', $profile->fresh()->name);
        $this->assertSame('My own edited biography.', $profile->fresh()->bio);
        $this->assertSame('My updated project', $project->fresh()->title);
        $this->assertEquals(42, $skill->fresh()->level);
        $this->assertDatabaseMissing('projects', ['slug' => $deletedProject->slug]);
        $this->assertDatabaseMissing('skills', ['name' => $deletedSkill->name]);
    }
}
