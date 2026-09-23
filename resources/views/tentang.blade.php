@extends('layouts.app')
@section('title', 'Tentang Kami')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">

    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $cms['about_title'] }}</h1>
    <p class="text-gray-600 leading-relaxed mb-8">{{ $cms['about_content'] }}</p>

    {{-- Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
        @foreach([
            ['value' => $cms['stat_customers'],   'label' => 'Customer Puas'],
            ['value' => $cms['stat_inspections'],  'label' => 'Inspeksi'],
            ['value' => $cms['stat_inspectors'],   'label' => 'Teknisi'],
            ['value' => $cms['stat_years'],        'label' => 'Tahun'],
        ] as $s)
        <div class="card p-5 text-center">
            <p class="text-3xl font-extrabold text-primary-600">{{ $s['value'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Visi --}}
    @if($cms['about_vision'])
    <div class="card p-6 mb-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Visi</h2>
        </div>
        <p class="text-gray-600">{{ $cms['about_vision'] }}</p>
    </div>
    @endif

    {{-- Misi --}}
    @if($cms['about_mission'])
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Misi</h2>
        </div>
        <div class="text-gray-600 whitespace-pre-line">{{ $cms['about_mission'] }}</div>
    </div>
    @endif

</div>
@endsection
