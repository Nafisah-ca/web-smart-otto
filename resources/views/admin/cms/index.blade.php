@extends('layouts.admin')
@section('page-title', 'CMS Konten')
@section('content')
<div class="space-y-4">
    <p class="text-sm text-gray-500">Pilih grup konten untuk diedit.</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
        $icons = ['general'=>'⚙️','hero'=>'🖼️','about'=>'ℹ️','stats'=>'📊','faq'=>'❓','footer'=>'🔗','ops'=>'🕐'];
        @endphp
        @foreach($groups as $group)
        <a href="{{ route('admin.cms.group', $group->group) }}" class="card p-5 text-center hover:border-primary-300 hover:shadow-md transition-all">
            <div class="text-3xl mb-2">{{ $icons[$group->group] ?? '📄' }}</div>
            <p class="font-medium text-gray-800 capitalize">{{ $group->group }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $group->total }} item</p>
        </a>
        @endforeach
    </div>
</div>
@endsection
