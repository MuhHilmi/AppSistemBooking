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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            // Identitas
            $table->string('site_name')->default('Booking Lapangan');
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();

            // Hero section
            $table->string('hero_badge_text')->nullable();
            $table->string('hero_headline')->nullable();
            $table->text('hero_subheadline')->nullable();
            $table->string('hero_image')->nullable();

            // Statistik
            $table->string('stat_1_value')->nullable();
            $table->string('stat_1_label')->nullable();
            $table->string('stat_2_value')->nullable();
            $table->string('stat_2_label')->nullable();
            $table->string('stat_3_value')->nullable();
            $table->string('stat_3_label')->nullable();

            // Footer
            $table->text('footer_description')->nullable();
            $table->string('support_email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('whatsapp_url')->nullable();
            $table->string('youtube_url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
