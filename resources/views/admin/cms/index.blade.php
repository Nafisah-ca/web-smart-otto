@extends('layouts.admin')
@section('page-title', 'CMS Konten Website')
@section('content')

<div class="max-w-3xl space-y-4">

    <div>
        <h2 class="text-lg font-semibold text-gray-800">Kelola Konten Website</h2>
        <p class="text-sm text-gray-500 mt-1">Pilih section untuk mulai mengedit konten yang tampil di halaman publik.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wide">
                    <th class="text-left px-5 py-3 font-semibold">Section</th>
                    <th class="text-left px-5 py-3 font-semibold hidden md:table-cell">Keterangan</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($sections as $key => $meta)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-800">{{ $meta['label'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 md:hidden">{{ $meta['desc'] }}</p>
                    </td>
                    <td class="px-5 py-4 text-gray-500 hidden md:table-cell">{{ $meta['desc'] }}</td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.cms.edit', $key) }}"
                           class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
