<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('district_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->foreignId('district_id')->constrained()->restrictOnDelete();
            $table->string('name', 160);
            $table->string('contact_name', 120)->nullable();
            $table->string('contact_designation', 120)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable();
            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('players_registered')->nullable();
            $table->unsignedInteger('coaches_registered')->nullable();
            $table->date('last_reviewed_on')->nullable();
            $table->text('supervision_notes')->nullable();
            $table->timestamps();

            $table->unique(['association_id', 'district_id']);
            $table->index(['association_id', 'status']);
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->foreignId('district_association_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sport_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 180);
            $table->string('slug', 190);
            $table->string('event_type', 24)->default('tournament');
            $table->string('level', 24)->default('state');
            $table->string('status', 24)->default('draft');
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->string('venue_name', 180)->nullable();
            $table->text('venue_address')->nullable();
            $table->decimal('map_lat', 10, 7)->nullable();
            $table->decimal('map_lng', 10, 7)->nullable();
            $table->string('hero_image', 60)->default('championship-stage');
            $table->string('hero_image_path', 255)->nullable();
            $table->string('theme', 20)->default('ink');
            $table->string('accent_color', 7)->nullable();
            $table->string('registration_url', 255)->nullable();
            $table->string('contact_email', 150)->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['association_id', 'slug']);
            $table->index(['association_id', 'status', 'starts_at']);
            $table->index(['association_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('district_associations');
    }
};
