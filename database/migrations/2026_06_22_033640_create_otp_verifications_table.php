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
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customers_id')->constrained('customers')->cascadeOnDelete();
            $table->string('otp');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expired_at');
            $table->boolean('is_used')->default(false);
            $table->timestamps();
            $table->index(['customers_id', 'is_used'], 'otp_customer_is_used_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
    }
};
