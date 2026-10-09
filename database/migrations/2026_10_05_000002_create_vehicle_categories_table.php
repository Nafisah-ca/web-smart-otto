<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel kategori kendaraan ──────────────────────────────
        Schema::create('vehicle_category', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);                // "Kat. 1"
            $table->string('description')->nullable();  // deskripsi opsional
            $table->decimal('price_min', 12, 2);        // 500000.00
            $table->decimal('price_max', 12, 2);        // 800000.00
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Tabel keyword model per kategori ─────────────────────
        Schema::create('vehicle_category_model', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->constrained('vehicle_category')
                  ->onDelete('cascade');
            $table->string('model_keyword', 100); // "Alphard", "Avanza", "Brio"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_category_model');
        Schema::dropIfExists('vehicle_category');
    }
};
