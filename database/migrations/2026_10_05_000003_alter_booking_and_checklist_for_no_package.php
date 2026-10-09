<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Perubahan skema untuk menghapus ketergantungan pada paket inspeksi:
 *
 * 1. booking.package_id  → nullable (booking baru tidak wajib punya paket)
 * 2. booking.category_id → tambah FK nullable ke vehicle_category
 * 3. booking.final_price → tambah kolom harga final per booking
 * 4. inspection_checklist_item.package_id → nullable
 *    (81 poin baru tidak terikat paket)
 *
 * Data lama TIDAK tersentuh:
 * - Booking lama: package_id tetap berisi, category_id & final_price = null
 * - Checklist item lama: package_id tetap berisi, is_active = false
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1 & 2 & 3: Alter tabel booking ───────────────────────
        Schema::table('booking', function (Blueprint $table) {
            // Lepas FK constraint lama — nama aktual: bookings_package_id_foreign
            $table->dropForeign('bookings_package_id_foreign');

            // Ubah package_id jadi nullable
            $table->foreignId('package_id')
                  ->nullable()
                  ->change();

            // Re-attach FK dengan nullable
            $table->foreign('package_id')
                  ->references('id')
                  ->on('inspection_package')
                  ->onDelete('set null');

            // Tambah category_id
            $table->foreignId('category_id')
                  ->nullable()
                  ->after('package_id')
                  ->constrained('vehicle_category')
                  ->onDelete('set null');

            // Tambah final_price
            $table->decimal('final_price', 12, 2)
                  ->nullable()
                  ->after('category_id');
        });

        // ── 4: Alter tabel inspection_checklist_item ──────────────
        Schema::table('inspection_checklist_item', function (Blueprint $table) {
            // Nama aktual: inspection_checklist_items_package_id_foreign
            $table->dropForeign('inspection_checklist_items_package_id_foreign');

            // Ubah package_id jadi nullable
            $table->foreignId('package_id')
                  ->nullable()
                  ->change();

            // Re-attach FK nullable
            $table->foreign('package_id')
                  ->references('id')
                  ->on('inspection_package')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'final_price']);
            $table->dropForeign(['package_id']);
            $table->foreignId('package_id')
                  ->nullable(false)
                  ->change();
            $table->foreign('package_id')
                  ->references('id')
                  ->on('inspection_package')
                  ->onDelete('cascade');
        });

        Schema::table('inspection_checklist_item', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->foreignId('package_id')
                  ->nullable(false)
                  ->change();
            $table->foreign('package_id')
                  ->references('id')
                  ->on('inspection_package')
                  ->onDelete('cascade');
        });
    }
};
