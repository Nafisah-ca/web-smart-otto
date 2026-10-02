<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    private array $sections = [
        'hero'              => ['label' => 'Hero / Banner Utama',   'desc' => 'Judul, subjudul, dan tombol utama halaman beranda.'],
        'stats'             => ['label' => 'Statistik',             'desc' => 'Angka-angka statistik yang ditampilkan di beranda.'],
        'kategori'          => ['label' => 'Kategori Kendaraan',    'desc' => 'Daftar kategori dan model kendaraan yang dilayani.'],
        'kenapa_harus_kami' => ['label' => 'Kenapa Harus Kami?',    'desc' => 'Section keunggulan 4 kartu di beranda.'],
        'keunggulan'        => ['label' => 'Keunggulan Kami (lama)','desc' => 'Section lama — sudah digantikan oleh "Kenapa Harus Kami?".'],
        'cara_kerja'        => ['label' => 'Cara Kerja',            'desc' => 'Langkah-langkah alur inspeksi kendaraan.'],
        'cta'               => ['label' => 'Call to Action',        'desc' => 'Teks ajakan di bagian bawah beranda.'],
        'kontak'            => ['label' => 'Kontak & Informasi',    'desc' => 'Telepon, email, alamat, dan jam operasional.'],
        'footer'            => ['label' => 'Footer',                'desc' => 'Tagline footer, hak cipta, dan media sosial.'],
        'general'           => ['label' => 'Pengaturan Umum',       'desc' => 'Tentang kami, visi misi, dan FAQ.'],
        'blog'              => ['label' => 'Artikel Blog',          'desc' => 'Kelola artikel blog yang tampil di halaman publik /blog.', 'external_url' => 'admin.posts.index'],
    ];

    // ── Index ──────────────────────────────────────────────────────────────
    public function index()
    {
        $sections = $this->sections;
        return view('admin.cms.index', compact('sections'));
    }

    // ── Edit ───────────────────────────────────────────────────────────────
    public function edit(string $section)
    {
        abort_unless(array_key_exists($section, $this->sections), 404);

        if ($section === 'blog') {
            return redirect()->route('admin.posts.index');
        }

        $data = $this->getData($section);
        $meta = $this->sections[$section];
        return view("admin.cms.sections.{$section}", compact('data', 'meta', 'section'));
    }

    // ── Save (dispatcher) ──────────────────────────────────────────────────
    public function save(Request $request, string $section)
    {
        abort_unless(array_key_exists($section, $this->sections), 404);

        match ($section) {
            'hero'              => $this->saveHero($request),
            'stats'             => $this->saveStats($request),
            'kategori'          => $this->saveKategori($request),
            'kenapa_harus_kami' => $this->saveKenapaHarusKami($request),
            'keunggulan'        => $this->saveKeunggulan($request),
            'cara_kerja'        => $this->saveCaraKerja($request),
            'cta'               => $this->saveSimple($request, ['cta_title','cta_subtitle','cta_button_text']),
            'kontak'            => $this->saveSimple($request, ['site_phone','site_email','site_address','site_maps_embed','ops_weekday','ops_saturday','ops_sunday']),
            'footer'            => $this->saveSimple($request, ['footer_tagline','footer_copyright','footer_social_ig','footer_social_fb','footer_social_wa']),
            'general'           => $this->saveGeneral($request),
            default             => abort(404),
        };

        return redirect()->route('admin.cms.edit', $section)
                         ->with('success', "Konten \"{$this->sections[$section]['label']}\" berhasil disimpan.");
    }

    // ── Save FAQ (khusus) ──────────────────────────────────────────────────
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
        return redirect()->route('admin.cms.edit', 'general')->with('success', 'FAQ berhasil disimpan.');
    }

    // ── getData per section ────────────────────────────────────────────────
    private function getData(string $section): array
    {
        $baseKeys = match ($section) {
            'hero'              => ['hero_title','hero_subtitle','hero_cta_text','hero_badge','hero_image'],
            'stats'             => ['stat_customers','stat_inspections','stat_inspectors','stat_years',
                                    'stat_icon_customers','stat_icon_inspections','stat_icon_inspectors','stat_icon_years'],
            'kategori'          => ['kategori_title','kategori_items'],
            'kenapa_harus_kami' => [
                'kenapa_judul','kenapa_subjudul',
                'kenapa_card1_icon','kenapa_card1_judul','kenapa_card1_desc',
                'kenapa_card2_icon','kenapa_card2_judul','kenapa_card2_desc','kenapa_card2_pdf',
                'kenapa_card3_icon','kenapa_card3_judul','kenapa_card3_desc',
                'kenapa_card4_icon','kenapa_card4_judul','kenapa_card4_desc',
            ],
            'keunggulan'        => ['keunggulan_title','keunggulan_subtitle','keunggulan_items'],
            'cara_kerja'        => ['cara_kerja_title','cara_kerja_subtitle','cara_kerja_steps'],
            'cta'               => ['cta_title','cta_subtitle','cta_button_text'],
            'kontak'            => ['site_phone','site_email','site_address','site_maps_embed','ops_weekday','ops_saturday','ops_sunday'],
            'footer'            => ['footer_tagline','footer_copyright','footer_social_ig','footer_social_fb','footer_social_wa'],
            'general'           => ['site_name','site_tagline','about_title','about_content','about_welcome','about_vision','about_mission','about_logo','faq_items'],
            default             => [],
        };

        $rows = CmsContent::whereIn('key', $baseKeys)->pluck('value', 'key');
        $data = [];
        foreach ($baseKeys as $k) {
            $data[$k] = $rows[$k] ?? '';
        }
        return $data;
    }

    // ── Save: Kenapa Harus Kami ────────────────────────────────────────────
    private function saveKenapaHarusKami(Request $request): void
    {
        $request->validate([
            'kenapa_judul'    => 'nullable|string|max:255',
            'kenapa_subjudul' => 'nullable|string|max:500',
            // teks per kartu
            'kenapa_card1_icon'  => 'nullable|string|max:100',
            'kenapa_card1_judul' => 'nullable|string|max:255',
            'kenapa_card1_desc'  => 'nullable|string',
            'kenapa_card2_icon'  => 'nullable|string|max:100',
            'kenapa_card2_judul' => 'nullable|string|max:255',
            'kenapa_card2_desc'  => 'nullable|string',
            'kenapa_card3_icon'  => 'nullable|string|max:100',
            'kenapa_card3_judul' => 'nullable|string|max:255',
            'kenapa_card3_desc'  => 'nullable|string',
            'kenapa_card4_icon'  => 'nullable|string|max:100',
            'kenapa_card4_judul' => 'nullable|string|max:255',
            'kenapa_card4_desc'  => 'nullable|string',
            // upload PDF
            'value_file'         => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Simpan field teks
        $textKeys = [
            'kenapa_judul','kenapa_subjudul',
            'kenapa_card1_icon','kenapa_card1_judul','kenapa_card1_desc',
            'kenapa_card2_icon','kenapa_card2_judul','kenapa_card2_desc',
            'kenapa_card3_icon','kenapa_card3_judul','kenapa_card3_desc',
            'kenapa_card4_icon','kenapa_card4_judul','kenapa_card4_desc',
        ];
        $this->saveSimple($request, $textKeys);

        // Simpan file PDF (kenapa_card2_pdf)
        $this->handleFileUpload($request, 'value_file', 'kenapa_card2_pdf', 'cms');
    }

    // ── Save: Hero ─────────────────────────────────────────────────────────
    private function saveHero(Request $request): void
    {
        $this->saveSimple($request, ['hero_title','hero_subtitle','hero_cta_text','hero_badge']);
        $this->handleImageUpload($request, 'hero_image', 'hero_image', 'cms');
    }

    // ── Save: Stats ────────────────────────────────────────────────────────
    private function saveStats(Request $request): void
    {
        $this->saveSimple($request, ['stat_customers','stat_inspections','stat_inspectors','stat_years']);
        foreach (['customers','inspections','inspectors','years'] as $slug) {
            $this->handleImageUpload($request, "stat_icon_{$slug}", "stat_icon_{$slug}", 'cms/icons');
        }
    }

    // ── Save: Kategori ─────────────────────────────────────────────────────
    private function saveKategori(Request $request): void
    {
        $this->setKey('kategori_title', $request->input('kategori_title', 'Pilih Kategori'));

        $names   = $request->input('kat_name',   []);
        $harga   = $request->input('kat_harga',  []);
        $models  = $request->input('kat_models', []);
        $keeps   = $request->input('kat_img_keep', []);
        $removes = $request->input('kat_img_remove', []);

        $items = [];
        foreach ($names as $i => $name) {
            if (trim($name) === '') continue;
            $imgPath = $keeps[$i] ?? '';
            if ($request->hasFile("kat_img.{$i}")) {
                if ($imgPath) $this->deletePublicFile($imgPath);
                $imgPath = $this->storePublicFile($request->file("kat_img.{$i}"), 'cms/kategori');
            } elseif (isset($removes[$i])) {
                if ($imgPath) $this->deletePublicFile($imgPath);
                $imgPath = '';
            }
            $modelArr = array_values(array_filter(array_map('trim', explode("\n", $models[$i] ?? ''))));
            $items[] = ['name' => trim($name), 'harga' => trim($harga[$i] ?? ''), 'img' => $imgPath, 'models' => $modelArr];
        }
        $this->setKey('kategori_items', json_encode($items, JSON_UNESCAPED_UNICODE));
    }

    // ── Save: Keunggulan (lama) ────────────────────────────────────────────
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

    // ── Save: Cara Kerja ───────────────────────────────────────────────────
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

    // ── Save: General ──────────────────────────────────────────────────────
    private function saveGeneral(Request $request): void
    {
        $this->saveSimple($request, ['site_name','site_tagline','about_title','about_welcome','about_content','about_vision','about_mission']);
        $this->handleImageUpload($request, 'about_logo', 'about_logo', 'cms');
    }

    // ── Helper: simpan banyak field teks sekaligus ────────────────────────
    private function saveSimple(Request $request, array $keys): void
    {
        foreach ($keys as $key) {
            if ($request->has($key)) {
                $this->setKey($key, $request->input($key, ''));
            }
        }
    }

    // ── Helper: upload image (simpan ke public/uploads/) ──────────────────
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
     * Helper: upload file (PDF dll) ke Storage::disk('public').
     * Input field: 'value_file' (bukan 'value' supaya tidak bentrok field teks).
     * Path disimpan relatif terhadap disk public (mis: "cms/laporan.pdf").
     */
    private function handleFileUpload(Request $request, string $inputName, string $cmsKey, string $folder = 'cms'): void
    {
        if ($request->hasFile($inputName)) {
            // Hapus file lama kalau ada
            $old = CmsContent::where('key', $cmsKey)->value('value');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }

            // Simpan file baru
            $file     = $request->file($inputName);
            $filename = uniqid('laporan_', true) . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs($folder, $filename, 'public');  // returns "cms/laporan_xxx.pdf"

            $this->setKey($cmsKey, $path);
        }
        // Kalau tidak ada file baru diupload — biarkan value lama tidak berubah
    }

    // ── Helper: simpan ke public/uploads/ (untuk gambar legacy) ──────────
    private function storePublicFile($file, string $folder): string
    {
        $dir = public_path("uploads/{$folder}");
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);
        return "uploads/{$folder}/{$filename}";
    }

    private function deletePublicFile(string $path): void
    {
        if (!$path || !str_starts_with($path, 'uploads/')) return;
        $full = public_path($path);
        if (file_exists($full)) unlink($full);
    }

    // ── Helper: upsert satu key ────────────────────────────────────────────
    private function setKey(string $key, string $value): void
    {
        CmsContent::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'label' => $key, 'group' => 'general', 'type' => 'text', 'is_active' => true]
        );
        Cache::forget("cms_{$key}");
    }
}
