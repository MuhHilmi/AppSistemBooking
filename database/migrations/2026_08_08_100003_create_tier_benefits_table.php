<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tier_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tier_id')->constrained('membership_tiers')->cascadeOnDelete();
            $table->foreignId('benefit_id')->constrained('benefits')->cascadeOnDelete();
            // null = tidak dibatasi (unlimited / berlaku terus selama jadi member tier ini)
            $table->unsignedInteger('usage_limit')->nullable();
            // null = one-time/unlimited, selain itu: day, week, month, cycle
            $table->string('limit_period')->nullable();
            $table->timestamps();

            $table->unique(['tier_id', 'benefit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tier_benefits');
    }
};
