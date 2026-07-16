<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('field_id')->constrained('fields')->cascadeOnDelete();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration');
            $table->decimal('price_per_hour', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', [
                'waiting_payment_method',
                'pending_payment',
                'paid',
                'confirmed',
                'completed',
                'canceled',
                'expired',
            ]);
            $table->string('payment_method')->nullable();
            $table->timestamp('reservation_expires_at')->nullable();
            $table->timestamp('payment_due_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->string('canceled_by')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
