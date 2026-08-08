<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->enum('type', ['earn', 'redeem', 'expire', 'adjustment']);
            // earn/adjustment(+) => positif, redeem/expire/adjustment(-) => negatif
            $table->integer('amount');
            // Hanya diisi untuk batch tipe earn, dipakai untuk redeem/expire FIFO
            $table->unsignedInteger('remaining_amount')->nullable();
            // Sumber transaksi: booking, streak, referral, benefit_redemption, manual_adjustment, dll
            $table->string('reference_type')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('note')->nullable();
            $table->date('expired_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'type']);
            $table->index('expired_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
