@extends('layouts.app')
@section('title', 'Kontak Kami')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Hubungi Kami</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            @foreach([
                ['icon'=>'📞','label'=>'Telepon','value'=>$cms['site_phone']],
                ['icon'=>'✉️','label'=>'Email','value'=>$cms['site_email']],
                ['icon'=>'📍','label'=>'Alamat','value'=>$cms['site_address']],
                ['icon'=>'🕐','label'=>'Jam Operasional','value'=>$cms['ops_weekday']."\n".$cms['ops_saturday']."\n".$cms['ops_sunday']],
            ] as $c)
            <div class="card p-5 flex gap-4">
                <span class="text-2xl">{{ $c['icon'] }}</span>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $c['label'] }}</p>
                    <p class="text-gray-800 mt-1 whitespace-pre-line">{{ $c['value'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card p-5">
            @if($cms['site_maps_embed'])
            <iframe src="{{ $cms['site_maps_embed'] }}" class="w-full h-64 rounded-lg border" allowfullscreen loading="lazy"></iframe>
            @endif
        </div>
    </div>
</div>
@endsection
