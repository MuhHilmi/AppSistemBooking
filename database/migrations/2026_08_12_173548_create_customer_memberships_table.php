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
        Schema::create('customer_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->unique()->constrained('customers')->cascadeOnDelete();
            $table->foreignId('tier_id')->constrained('membership_tiers');
            $table->unsignedInteger('current_point')->default(0);
            $table->unsignedInteger('qualification_booking_count')->default(0);
            $table->decimal('qualification_spend', 12, 2)->default(0);
            $table->date('cycle_start_at');
            $table->date('cycle_end_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_memberships');
    }
};
