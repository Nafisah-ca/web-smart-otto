<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('inspection_packages')->onDelete('cascade');
            $table->string('item_name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_checklist_items');
    }
};
