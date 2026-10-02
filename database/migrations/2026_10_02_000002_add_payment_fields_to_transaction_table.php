<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->string('payment_status', 50)->default('unpaid')->change();
            $table->string('payment_channel')->nullable()->after('payment_method');
            $table->string('payment_proof')->nullable()->after('payment_channel');
            $table->string('payment_reference')->nullable()->after('payment_proof');
            $table->text('admin_notes')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->dropColumn(['payment_channel', 'payment_proof', 'payment_reference', 'admin_notes']);
        });
    }
};
