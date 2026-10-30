<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('tagline');
            $table->text('bio');
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('github_url', 2048)->nullable();
            $table->string('linkedin_url', 2048)->nullable();
            $table->string('instagram_url', 2048)->nullable();
            $table->string('phone', 30)->nullable();
            $table->boolean('available')->default(true);
            $table->timestamps();
        });
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->text('description');
            $table->json('tech_stack')->nullable();
            $table->string('project_url', 2048)->nullable();
            $table->string('github_url', 2048)->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('accent_color', 7)->default('#b8c6a0');
            $table->timestamps();
        });
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->unsignedTinyInteger('level')->default(80);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->timestamp('read_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('profiles');
    }
};
