@extends('layouts.admin')
@section('page-title', 'CMS: ' . ucfirst($group))
@section('content')
<div class="space-y-4 max-w-3xl">
    <a href="{{ route('admin.cms.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>

    @foreach($contents as $content)
    <div class="card p-5">
        <form method="POST" action="{{ route('admin.cms.update', $content) }}">
            @csrf @method('PUT')
            <label class="form-label">{{ $content->label }} <span class="text-xs text-gray-400 font-normal">({{ $content->key }})</span></label>
            @if($content->type === 'textarea' || $content->type === 'html')
            <textarea name="value" rows="4" class="form-input font-mono text-sm">{{ $content->value }}</textarea>
            @elseif($content->type === 'json')
            <textarea name="value" rows="8" class="form-input font-mono text-sm">{{ $content->value }}</textarea>
            <p class="text-xs text-gray-400 mt-1">Format JSON</p>
            @else
            <input type="text" name="value" value="{{ $content->value }}" class="form-input">
            @endif
            <button type="submit" class="btn-primary btn-sm mt-2">Simpan</button>
        </form>
    </div>
    @endforeach
</div>
@endsection
