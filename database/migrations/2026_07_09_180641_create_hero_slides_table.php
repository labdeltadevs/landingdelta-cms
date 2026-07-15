<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image_path');
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('slideable_type')->nullable();
            $table->unsignedBigInteger('slideable_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();

            $table->index(['slideable_type', 'slideable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
