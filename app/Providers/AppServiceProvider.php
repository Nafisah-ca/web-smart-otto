<?php

namespace App\Providers;

use App\Models\CmsContent;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share CMS site settings to all views
        View::composer('*', function ($view) {
            try {
                $view->with('siteName',        CmsContent::get('site_name',        'Smart Otto'));
                $view->with('siteTagline',     CmsContent::get('site_tagline',     'Inspeksi Kendaraan Profesional & Terpercaya'));
                $view->with('sitePhone',       CmsContent::get('site_phone',       ''));
                $view->with('siteEmail',       CmsContent::get('site_email',       ''));
                $view->with('siteAddress',     CmsContent::get('site_address',     ''));
                $view->with('footerTagline',   CmsContent::get('footer_tagline',   ''));
                $view->with('footerCopyright', CmsContent::get('footer_copyright', '© ' . date('Y') . ' Smart Otto. Seluruh hak cipta dilindungi.'));
                $view->with('footerSocialIg',  CmsContent::get('footer_social_ig', ''));
                $view->with('footerSocialFb',  CmsContent::get('footer_social_fb', ''));
                $view->with('footerSocialWa',  CmsContent::get('footer_social_wa', ''));
            } catch (\Exception $e) {
                // Tabel belum ada (saat migrate), skip
            }
        });
    }
}
