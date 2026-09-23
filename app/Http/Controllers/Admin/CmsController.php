<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CmsController extends Controller
{
    private array $sections = [
        'hero'       => ['label' => 'Hero / Banner Utama',  'desc' => 'Judul, subjudul, dan tombol utama halaman beranda.'],
        'stats'      => ['label' => 'Statistik',            'desc' => 'Angka-angka statistik yang ditampilkan di beranda.'],
        'keunggulan' => ['label' => 'Keunggulan Kami',      'desc' => 'Daftar keunggulan layanan Smart Otto.'],
        'cara_kerja' => ['label' => 'Cara Kerja',           'desc' => 'Langkah-langkah alur inspeksi kendaraan.'],
        'cta'        => ['label' => 'Call to Action',       'desc' => 'Teks ajakan di bagian bawah beranda.'],
        'kontak'     => ['label' => 'Kontak & Informasi',   'desc' => 'Telepon, email, alamat, dan jam operasional.'],
        'footer'     => ['label' => 'Footer',               'desc' => 'Tagline footer, hak cipta, dan media sosial.'],
        'general'    => ['label' => 'Pengaturan Umum',      'desc' => 'Tentang kami, visi misi, dan FAQ.'],
    ];

    // ── INDEX ────────────────────────────────────────────────────────
    public function index()
    {
        $sections = $this->sections;
        return view('admin.cms.index', compact('sections'));
    }

    // ── EDIT ─────────────────────────────────────────────────────────
    public function edit(string $section)
    {
        abort_unless(array_key_exists($section, $this->sections), 404);
        $data = $this->getData($section);
        $meta = $this->sections[$section];
        return view("admin.cms.sections.{$section}", compact('data', 'meta', 'section'));
    }

    // ── SAVE ─────────────────────────────────────────────────────────
    public function save(Request $request, string $section)
    {
        abort_unless(array_key_exists($section, $this->sections), 404);

        match ($section) {
            'hero'       => $this->saveHero($request),
            'stats'      => $this->saveStats($request),
            'keunggulan' => $this->saveKeunggulan($request),
            'cara_kerja' => $this->saveCaraKerja($request),
            'cta'        => $this->saveSimple($request, ['cta_title','cta_subtitle','cta_button_text']),
            'kontak'     => $this->saveSimple($request, ['site_phone','site_email','site_address','site_maps_embed','ops_weekday','ops_saturday','ops_sunday']),
            'footer'     => $this->saveSimple($request, ['footer_tagline','footer_copyright','footer_social_ig','footer_social_fb','footer_social_wa']),
            'general'    => $this->saveSimple($request, ['site_name','site_tagline','about_title','about_content','about_vision','about_mission']),
            default      => abort(404),
        };

        return redirect()->route('admin.cms.edit', $section)
                         ->with('success', "Konten \"{$this->sections[$section]['label']}\" berhasil disimpan.");
    }

    // ── SAVE FAQ ─────────────────────────────────────────────────────
    public function saveFaq(Request $request)
    {
        $items     = [];
        $questions = $request->input('faq_q', []);
        $answers   = $request->input('faq_a', []);
        foreach ($questions as $i => $q) {
            if (trim($q) === '' && trim($answers[$i] ?? '') === '') continue;
            $items[] = ['q' => trim($q), 'a' => trim($answers[$i] ?? '')];
        }
        $this->setKey('faq_items', json_encode($items, JSON_UNESCAPED_UNICODE));

        return redirect()->route('admin.cms.edit', 'general')
                         ->with('success', 'FAQ berhasil disimpan.');
    }

    // ── getData ──────────────────────────────────────────────────────
    private function getData(string $section): array
    {
        $baseKeys = match ($section) {
            'hero'       => ['hero_title','hero_subtitle','hero_cta_text','hero_badge','hero_image'],
            'stats'      => ['stat_customers','stat_inspections','stat_inspectors','stat_years',
                             'stat_icon_customers','stat_icon_inspections','stat_icon_inspectors','stat_icon_years'],
            'keunggulan' => ['keunggulan_title','keunggulan_subtitle','keunggulan_items'],
            'cara_kerja' => ['cara_kerja_title','cara_kerja_subtitle','cara_kerja_steps'],
            'cta'        => ['cta_title','cta_subtitle','cta_button_text'],
            'kontak'     => ['site_phone','site_email','site_address','site_maps_embed','ops_weekday','ops_saturday','ops_sunday'],
            'footer'     => ['footer_tagline','footer_copyright','footer_social_ig','footer_social_fb','footer_social_wa'],
            'general'    => ['site_name','site_tagline','about_title','about_content','about_vision','about_mission','faq_items'],
            default      => [],
        };

        $rows = CmsContent::whereIn('key', $baseKeys)->pluck('value', 'key');
        $data = [];
        foreach ($baseKeys as $k) {
            $data[$k] = $rows[$k] ?? '';
        }
        return $data;
    }

    // ── saveHero ─────────────────────────────────────────────────────
    private function saveHero(Request $request): void
    {
        $this->saveSimple($request, ['hero_title','hero_subtitle','hero_cta_text','hero_badge']);
        $this->handleImageUpload($request, 'hero_image', 'hero_image', 'cms');
    }

    // ── saveStats ────────────────────────────────────────────────────
    private function saveStats(Request $request): void
    {
        $this->saveSimple($request, ['stat_customers','stat_inspections','stat_inspectors','stat_years']);
        foreach (['customers','inspections','inspectors','years'] as $slug) {
            $this->handleImageUpload($request, "stat_icon_{$slug}", "stat_icon_{$slug}", 'cms/icons');
        }
    }

    // ── saveKeunggulan ───────────────────────────────────────────────
    private function saveKeunggulan(Request $request): void
    {
        $this->setKey('keunggulan_title',    $request->input('keunggulan_title', ''));
        $this->setKey('keunggulan_subtitle', $request->input('keunggulan_subtitle', ''));

        $titles  = $request->input('item_title', []);
        $descs   = $request->input('item_desc',  []);
        $keeps   = $request->input('item_icon_keep', []);
        $removes = $request->input('item_icon_remove', []);

        $items = [];
        foreach ($titles as $i => $title) {
            if (trim($title) === '' && trim($descs[$i] ?? '') === '') continue;

            $iconPath = $keeps[$i] ?? '';

            if ($request->hasFile("item_icon.{$i}")) {
                if ($iconPath) $this->deletePublicFile($iconPath);
                $iconPath = $this->storePublicFile($request->file("item_icon.{$i}"), 'cms/icons');
            } elseif (isset($removes[$i])) {
                if ($iconPath) $this->deletePublicFile($iconPath);
                $iconPath = '';
            }

            $items[] = ['title' => trim($title), 'desc' => trim($descs[$i] ?? ''), 'icon' => $iconPath];
        }

        $this->setKey('keunggulan_items', json_encode($items, JSON_UNESCAPED_UNICODE));
    }

    // ── saveCaraKerja ────────────────────────────────────────────────
    private function saveCaraKerja(Request $request): void
    {
        $this->setKey('cara_kerja_title',    $request->input('cara_kerja_title', ''));
        $this->setKey('cara_kerja_subtitle', $request->input('cara_kerja_subtitle', ''));

        $titles  = $request->input('step_title', []);
        $descs   = $request->input('step_desc',  []);
        $keeps   = $request->input('step_icon_keep', []);
        $removes = $request->input('step_icon_remove', []);

        $items = [];
        foreach ($titles as $i => $title) {
            if (trim($title) === '' && trim($descs[$i] ?? '') === '') continue;

            $iconPath = $keeps[$i] ?? '';

            if ($request->hasFile("step_icon.{$i}")) {
                if ($iconPath) $this->deletePublicFile($iconPath);
                $iconPath = $this->storePublicFile($request->file("step_icon.{$i}"), 'cms/icons');
            } elseif (isset($removes[$i])) {
                if ($iconPath) $this->deletePublicFile($iconPath);
                $iconPath = '';
            }

            $items[] = ['step' => (string)($i + 1), 'title' => trim($title), 'desc' => trim($descs[$i] ?? ''), 'icon' => $iconPath];
        }

        $this->setKey('cara_kerja_steps', json_encode($items, JSON_UNESCAPED_UNICODE));
    }

    // ── saveSimple ───────────────────────────────────────────────────
    private function saveSimple(Request $request, array $keys): void
    {
        foreach ($keys as $key) {
            if ($request->has($key)) {
                $this->setKey($key, $request->input($key, ''));
            }
        }
    }

    // ── handleImageUpload — simpan ke public/uploads langsung ────────
    private function handleImageUpload(Request $request, string $inputName, string $cmsKey, string $folder = 'cms'): void
    {
        if ($request->hasFile($inputName)) {
            $old = CmsContent::where('key', $cmsKey)->value('value');
            if ($old) $this->deletePublicFile($old);
            $path = $this->storePublicFile($request->file($inputName), $folder);
            $this->setKey($cmsKey, $path);
        } elseif ($request->boolean("{$inputName}_remove")) {
            $old = CmsContent::where('key', $cmsKey)->value('value');
            if ($old) $this->deletePublicFile($old);
            $this->setKey($cmsKey, '');
        }
    }

    /**
     * Simpan file langsung ke public/uploads/{folder}/
     * Return path relatif dari public (contoh: uploads/cms/icons/abc123.png)
     */
    private function storePublicFile($file, string $folder): string
    {
        $dir = public_path("uploads/{$folder}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename  = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);
        return "uploads/{$folder}/{$filename}";
    }

    /**
     * Hapus file dari public/
     */
    private function deletePublicFile(string $path): void
    {
        // Jangan hapus jika masih path emoji atau kosong
        if (!$path || !str_starts_with($path, 'uploads/')) return;
        $full = public_path($path);
        if (file_exists($full)) {
            unlink($full);
        }
    }

    // ── setKey ───────────────────────────────────────────────────────
    private function setKey(string $key, string $value): void
    {
        CmsContent::updateOrCreate(
            ['key' => $key],
            [
                'value'     => $value,
                'label'     => $key,   // fallback label supaya kolom tidak kosong
                'group'     => 'general',
                'type'      => 'text',
                'is_active' => true,
            ]
        );
        Cache::forget("cms_{$key}");
    }
}
