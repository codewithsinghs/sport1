<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->string('page', 30)->default('home');
            $table->string('type', 24)->default('rich_text');
            $table->string('eyebrow', 80)->nullable();
            $table->string('title', 180)->nullable();
            $table->longText('body')->nullable();
            $table->string('image', 60)->nullable();
            $table->string('link_label', 80)->nullable();
            $table->string('link_url', 255)->nullable();
            $table->json('settings')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['association_id', 'page', 'is_active', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
