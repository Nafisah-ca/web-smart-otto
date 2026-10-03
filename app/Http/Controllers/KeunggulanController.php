<?php

namespace App\Http\Controllers;

use App\Models\CmsContent;

class KeunggulanController extends Controller
{
    private array $pages = [
        'alat-canggih'    => ['group' => 'keunggulan_alat_canggih',    'icon' => 'monitor',        'color' => 'blue'],
        'profesional'     => ['group' => 'keunggulan_profesional',     'icon' => 'shield-check',   'color' => 'green'],
        'transparan'      => ['group' => 'keunggulan_transparan',      'icon' => 'eye',            'color' => 'purple'],
        'titik-inspeksi'  => ['group' => 'keunggulan_titik_inspeksi',  'icon' => 'clipboard-list', 'color' => 'orange'],
    ];

    public function show(string $slug)
    {
        abort_unless(array_key_exists($slug, $this->pages), 404);

        $meta  = $this->pages[$slug];
        $group = $meta['group'];

        $data = CmsContent::where('group', $group)
            ->where('is_active', true)
            ->pluck('value', 'key');

        $page = [
            'slug'     => $slug,
            'icon'     => $meta['icon'],
            'color'    => $meta['color'],
            'judul'    => $data["{$group}_judul"]    ?? ucwords(str_replace('-', ' ', $slug)),
            'subjudul' => $data["{$group}_subjudul"] ?? '',
            'intro'    => $data["{$group}_intro"]    ?? '',
            'poin'     => json_decode($data["{$group}_poin"]  ?? '[]',   true) ?? [],
            'extra'    => json_decode($data["{$group}_extra"] ?? 'null', true),
        ];

        return view('keunggulan.show', compact('page'));
    }
}
