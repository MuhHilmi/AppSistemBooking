<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benefits', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', [
                'discount',
                'voucher',
                'free_item',
                'priority_booking',
                'flexible_reschedule',
                'dedicated_support',
                'exclusive_promo',
            ]);
            $table->enum('value_type', ['percentage', 'fixed_amount', 'boolean', 'text']);
            $table->string('value')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benefits');
    }
};
