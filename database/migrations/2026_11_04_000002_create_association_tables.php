<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('associations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('short_name', 50)->nullable();
            $table->string('slug', 100)->unique();
            $table->foreignId('state_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('website', 200)->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('association_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->string('domain', 190)->unique();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('association_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->string('key', 100);
            $table->json('value')->nullable();
            $table->timestamps();

            $table->unique(['association_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('association_settings');
        Schema::dropIfExists('association_domains');
        Schema::dropIfExists('associations');
    }
};
