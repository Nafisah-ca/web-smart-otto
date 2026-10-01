<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable()->default('Inspeksi Kendaraan');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('thumbnail_url')->nullable();
            $table->string('author_name')->default('Tim Smart Otto');
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            // CTA banner fields
            $table->boolean('cta_enabled')->default(false);
            $table->string('cta_title')->nullable();
            $table->string('cta_subtitle')->nullable();
            $table->string('cta_button_text')->nullable()->default('Booking Sekarang');
            $table->string('cta_button_url')->nullable();
            $table->string('cta_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
