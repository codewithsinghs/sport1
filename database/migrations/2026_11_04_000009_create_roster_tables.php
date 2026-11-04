<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code', 40)->nullable()->unique();
            $table->foreignId('owner_association_id')->nullable()->constrained('associations')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 120);
            $table->string('slug', 150)->nullable()->unique();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 10)->nullable();
            $table->foreignId('state_id')->nullable()->constrained()->nullOnDelete();
            $table->string('city', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->string('primary_role', 24)->default('player');
            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['name', 'is_active']);
            $table->index(['gender', 'date_of_birth']);
        });

        Schema::create('player_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source', 24)->default('registry');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['association_id', 'player_id']);
        });

        Schema::create('district_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->foreignId('district_association_id')->constrained()->restrictOnDelete();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('role', 24)->default('player');
            $table->string('status', 24)->default('active');
            $table->unsignedSmallInteger('jersey_number')->nullable();
            $table->date('joined_on')->nullable();
            $table->date('left_on')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['district_association_id', 'status']);
            $table->index(['association_id', 'player_id']);
        });

        Schema::create('state_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('district_team_member_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('role', 24)->default('player');
            $table->string('status', 24)->default('active');
            $table->unsignedSmallInteger('jersey_number')->nullable();
            $table->date('selected_on')->nullable();
            $table->date('expires_on')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['association_id', 'status']);
            $table->index(['association_id', 'player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('state_team_members');
        Schema::dropIfExists('district_team_members');
        Schema::dropIfExists('player_associations');
        Schema::dropIfExists('players');
    }
};
