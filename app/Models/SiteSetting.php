<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'tagline',
        'logo',
        'hero_badge_text',
        'hero_headline',
        'hero_subheadline',
        'hero_image',
        'stat_1_value',
        'stat_1_label',
        'stat_2_value',
        'stat_2_label',
        'stat_3_value',
        'stat_3_label',
        'footer_description',
        'support_email',
        'phone',
        'address',
        'facebook_url',
        'instagram_url',
        'whatsapp_url',
        'youtube_url',
        'receipt_header',
        'receipt_footer',
    ];

    protected $appends = ['logo_url', 'hero_image_url'];

    /**
     * Ambil satu-satunya baris pengaturan situs, buat dengan nilai default
     * kalau belum pernah diisi sama sekali.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'site_name' => 'Booking Lapangan',
            'tagline' => 'Sport Reservation',
            'hero_badge_text' => 'Booking Lapangan Kini Lebih Mudah',
            'hero_headline' => 'Booking Lapangan Tanpa Ribet.',
            'hero_subheadline' => 'Temukan lapangan futsal, badminton, basket, tenis, dan berbagai olahraga lainnya. Pilih jadwal yang tersedia, lakukan pembayaran, dan nikmati pengalaman booking yang cepat, aman, dan praktis.',
            'stat_1_value' => '300+',
            'stat_1_label' => 'Lapangan',
            'stat_2_value' => '4.500+',
            'stat_2_label' => 'Booking',
            'stat_3_value' => '98%',
            'stat_3_label' => 'Kepuasan',
            'footer_description' => 'Platform booking lapangan olahraga yang membantu pelanggan menemukan, membandingkan, dan memesan lapangan secara online dengan proses yang cepat, mudah, dan aman.',
            'support_email' => 'support@bookinglapangan.com',
            'phone' => '+62 812-3456-7890',
            'address' => 'Bandung, Jawa Barat',
            'receipt_header' => 'Terima kasih telah melakukan booking.',
            'receipt_footer' => "Struk ini adalah bukti booking yang sah.\nSimpan struk ini sebagai bukti saat datang ke lokasi."
        ]);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->hero_image ? asset('storage/' . $this->hero_image) : null;
    }
}
