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
        Schema::create('tier_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('from_tier_id')->nullable()->constrained('membership_tiers')->nullOnDelete();
            $table->foreignId('to_tier_id')->constrained('membership_tiers');
            $table->enum('reason', ['initial', 'upgrade', 'downgrade', 'renewal', 'manual_adjustment']);
            $table->timestamp('changed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tier_histories');
    }
};
