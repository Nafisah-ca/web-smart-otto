<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CmsController extends Controller
{
    public function index()
    {
        $groups = CmsContent::selectRaw('`group`, COUNT(*) as total')
            ->groupBy('group')
            ->orderBy('group')
            ->get();
        return view('admin.cms.index', compact('groups'));
    }

    public function group(string $group)
    {
        $contents = CmsContent::where('group', $group)->orderBy('key')->get();
        return view('admin.cms.group', compact('contents', 'group'));
    }

    public function update(Request $request, CmsContent $content)
    {
        $request->validate([
            'value' => 'nullable|string',
        ]);

        $content->update(['value' => $request->value]);
        Cache::forget("cms_{$content->key}");

        return back()->with('success', "Konten '{$content->label}' berhasil diperbarui.");
    }
}
