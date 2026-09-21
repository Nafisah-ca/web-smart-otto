@extends('layouts.app')
@section('title', 'Tentang Kami')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $cms['about_title'] }}</h1>
    <p class="text-gray-600 leading-relaxed mb-8">{{ $cms['about_content'] }}</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
        @foreach([
            ['value'=>$cms['stat_customers'],'label'=>'Customer Puas'],
            ['value'=>$cms['stat_inspections'],'label'=>'Inspeksi'],
            ['value'=>$cms['stat_inspectors'],'label'=>'Teknisi'],
            ['value'=>$cms['stat_years'],'label'=>'Tahun'],
        ] as $s)
        <div class="card p-5 text-center">
            <p class="text-3xl font-extrabold text-primary-600">{{ $s['value'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    @if($cms['about_vision'])
    <div class="card p-6 mb-4">
        <h2 class="text-xl font-bold text-gray-900 mb-2">🎯 Visi</h2>
        <p class="text-gray-600">{{ $cms['about_vision'] }}</p>
    </div>
    @endif
    @if($cms['about_mission'])
    <div class="card p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2">🚀 Misi</h2>
        <div class="text-gray-600 whitespace-pre-line">{{ $cms['about_mission'] }}</div>
    </div>
    @endif
</div>
@endsection
