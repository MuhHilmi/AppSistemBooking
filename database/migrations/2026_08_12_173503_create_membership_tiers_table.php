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
        Schema::create('membership_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedTinyInteger('level')->unique();
            $table->unsignedInteger('min_booking')->default(0);
            $table->decimal('min_spend', 12, 2)->default(0);
            $table->unsignedInteger('evaluation_period_days')->default(90);
            $table->unsignedInteger('booking_advance_days')->default(1);
            $table->decimal('point_multiplier', 4, 2)->default(1.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_tiers');
    }
};
