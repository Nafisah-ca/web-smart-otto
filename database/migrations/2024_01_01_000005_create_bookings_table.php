<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained('inspection_packages')->onDelete('cascade');
            $table->foreignId('inspector_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->enum('status', [
                'pending',
                'confirmed',
                'waiting',
                'on_progress',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
