<?php

namespace App\Http\Controllers;

use App\Models\CmsContent;
use App\Models\InspectionPackage;

class HomeController extends Controller
{
    public function index()
    {
        $packages = InspectionPackage::active()->get();

        $cms = [
            'hero_title'     => CmsContent::get('hero_title'),
            'hero_subtitle'  => CmsContent::get('hero_subtitle'),
            'hero_cta_text'  => CmsContent::get('hero_cta_text', 'Booking Sekarang'),
            'about_title'    => CmsContent::get('about_title'),
            'about_content'  => CmsContent::get('about_content'),
            'stat_customers' => CmsContent::get('stat_customers', '5.000+'),
            'stat_inspections'=> CmsContent::get('stat_inspections', '12.000+'),
            'stat_inspectors'=> CmsContent::get('stat_inspectors', '25+'),
            'stat_years'     => CmsContent::get('stat_years', '10+'),
            'faq_items'      => json_decode(CmsContent::get('faq_items', '[]'), true) ?? [],
            'ops_weekday'    => CmsContent::get('ops_weekday'),
            'ops_saturday'   => CmsContent::get('ops_saturday'),
            'ops_sunday'     => CmsContent::get('ops_sunday'),
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
            'about_title'   => CmsContent::get('about_title'),
            'about_content' => CmsContent::get('about_content'),
            'about_vision'  => CmsContent::get('about_vision'),
            'about_mission' => CmsContent::get('about_mission'),
            'stat_customers'=> CmsContent::get('stat_customers', '5.000+'),
            'stat_inspections'=> CmsContent::get('stat_inspections', '12.000+'),
            'stat_inspectors'=> CmsContent::get('stat_inspectors', '25+'),
            'stat_years'    => CmsContent::get('stat_years', '10+'),
        ];
        return view('tentang', compact('cms'));
    }

    public function kontak()
    {
        $cms = [
            'site_phone'     => CmsContent::get('site_phone'),
            'site_email'     => CmsContent::get('site_email'),
            'site_address'   => CmsContent::get('site_address'),
            'site_maps_embed'=> CmsContent::get('site_maps_embed'),
            'ops_weekday'    => CmsContent::get('ops_weekday'),
            'ops_saturday'   => CmsContent::get('ops_saturday'),
            'ops_sunday'     => CmsContent::get('ops_sunday'),
        ];
        return view('kontak', compact('cms'));
    }

    public function showPackage(\App\Models\InspectionPackage $package)
    {
        $package->load('checklistItems');
        return view('paket-detail', compact('package'));
    }
}
