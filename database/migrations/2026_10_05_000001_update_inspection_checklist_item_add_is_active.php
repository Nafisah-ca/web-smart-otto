<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds is_active flag to inspection_checklist_item.
 *
 * - is_active = true  → item aktif (poin baru dari PDF)
 * - is_active = false → item lama yang dinonaktifkan (tidak dihapus agar data
 *                        inspeksi lama di checklist_json tetap valid)
 *
 * package_id tetap ada dan wajib — semua 81 poin baru akan diinsert
 * untuk setiap paket, sehingga relasi tidak berubah.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspection_checklist_item', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('inspection_checklist_item', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
