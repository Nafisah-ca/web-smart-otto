<?php

namespace App\Http\Controllers;

use App\Models\CmsContent;
use App\Models\InspectionPackage;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $packages = InspectionPackage::active()->get();

        $cms = [
            // Hero
            'hero_title'    => CmsContent::get('hero_title'),
            'hero_subtitle' => CmsContent::get('hero_subtitle'),
            'hero_cta_text' => CmsContent::get('hero_cta_text', 'Booking Sekarang'),
            'hero_badge'    => CmsContent::get('hero_badge', 'Teknisi Bersertifikat & Berpengalaman'),
            'hero_image'    => CmsContent::get('hero_image', ''),

            // Statistik
            'stat_customers'        => CmsContent::get('stat_customers',   '500+'),
            'stat_inspections'      => CmsContent::get('stat_inspections', '1.200+'),
            'stat_inspectors'       => CmsContent::get('stat_inspectors',  '15+'),
            'stat_years'            => CmsContent::get('stat_years',       '5+'),
            'stat_icon_customers'   => CmsContent::get('stat_icon_customers',   ''),
            'stat_icon_inspections' => CmsContent::get('stat_icon_inspections', ''),
            'stat_icon_inspectors'  => CmsContent::get('stat_icon_inspectors',  ''),
            'stat_icon_years'       => CmsContent::get('stat_icon_years',       ''),

            // Keunggulan
            'keunggulan_title'    => CmsContent::get('keunggulan_title',    'Mengapa Memilih Smart Otto?'),
            'keunggulan_subtitle' => CmsContent::get('keunggulan_subtitle', ''),
            'keunggulan_items'    => json_decode(CmsContent::get('keunggulan_items', '[]'), true) ?? [],

            // Cara Kerja
            'cara_kerja_title'    => CmsContent::get('cara_kerja_title',    'Cara Kerja Smart Otto'),
            'cara_kerja_subtitle' => CmsContent::get('cara_kerja_subtitle', ''),
            'cara_kerja_steps'    => json_decode(CmsContent::get('cara_kerja_steps', '[]'), true) ?? [],

            // CTA
            'cta_title'       => CmsContent::get('cta_title',       'Siap Booking Inspeksi?'),
            'cta_subtitle'    => CmsContent::get('cta_subtitle',    'Pastikan kendaraan Anda aman sebelum berkendara.'),
            'cta_button_text' => CmsContent::get('cta_button_text', 'Booking Sekarang'),

            // FAQ
            'faq_items' => json_decode(CmsContent::get('faq_items', '[]'), true) ?? [],
        ];

        return view('home', compact('packages', 'cms'));
    }

    public function layanan()
    {
        $packages = InspectionPackage::active()->with('checklistItems')->get();
        return view('layanan', compact('packages'));
    }

    public function tentang()
    {
        $cms = [
            'about_title'      => CmsContent::get('about_title'),
            'about_content'    => CmsContent::get('about_content'),
            'about_vision'     => CmsContent::get('about_vision'),
            'about_mission'    => CmsContent::get('about_mission'),
            'stat_customers'   => CmsContent::get('stat_customers',   '500+'),
            'stat_inspections' => CmsContent::get('stat_inspections', '1.200+'),
            'stat_inspectors'  => CmsContent::get('stat_inspectors',  '15+'),
            'stat_years'       => CmsContent::get('stat_years',       '5+'),
        ];
        return view('tentang', compact('cms'));
    }

    public function kontak()
    {
        $cms = [
            'site_phone'      => CmsContent::get('site_phone'),
            'site_email'      => CmsContent::get('site_email'),
            'site_address'    => CmsContent::get('site_address'),
            'site_maps_embed' => CmsContent::get('site_maps_embed'),
            'ops_weekday'     => CmsContent::get('ops_weekday', 'Senin - Jumat: 08.00 - 17.00 WIB'),
            'ops_saturday'    => CmsContent::get('ops_saturday', 'Sabtu: 08.00 - 15.00 WIB'),
            'ops_sunday'      => CmsContent::get('ops_sunday',   'Minggu: Tutup'),
        ];
        return view('kontak', compact('cms'));
    }

    public function showPackage(\App\Models\InspectionPackage $package)
    {
        $package->load('checklistItems');
        return view('paket-detail', compact('package'));
    }
}
