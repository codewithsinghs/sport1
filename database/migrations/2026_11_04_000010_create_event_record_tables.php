<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('district_association_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('state_team_member_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('role', 24)->default('player');
            $table->string('status', 24)->default('confirmed');
            $table->unsignedSmallInteger('jersey_number')->nullable();
            $table->string('team_name', 150)->nullable();
            $table->unsignedSmallInteger('placement')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('recorded_at')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'status']);
            $table->index(['event_id', 'player_id']);
        });

        Schema::create('event_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('caption', 180)->nullable();
            $table->string('alt', 255);
            $table->string('file_path', 255);
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->string('credit', 120)->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index(['event_id', 'is_public', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_media');
        Schema::dropIfExists('event_entries');
    }
};
