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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('venues')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('sport_type', [
                'futsal',
                'badminton',
                'basket',
                'tennis',
                'voli'
            ]);
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->decimal('price_per_hour',10,2);
            $table->integer('capacity')->default(10);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
