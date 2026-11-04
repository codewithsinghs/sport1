<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->decimal('fee', 10, 2)->default(0);
            $table->string('billing_period', 20)->default('annual');
            $table->boolean('requires_approval')->default(true);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['association_id', 'slug']);
            $table->index(['association_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_categories');
    }
};
