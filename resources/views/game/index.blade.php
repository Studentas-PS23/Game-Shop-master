@extends('layouts.app')

@section('title', 'Žaidimų sąrašas')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <h2 class="text-2xl font-bold mb-6 text-[#00ffff]">Žaidimų sąrašas</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
        @foreach($games as $game)
            <a href="{{ route('games.show', $game->slug) }}" class="block bg-[#181818] rounded-xl shadow-lg border border-[#2c2c2c] card-hover overflow-hidden">
                <div class="w-full h-64 bg-black flex items-center justify-center">
                    <img src="{{ asset($game->image) }}" alt="{{ $game->name }}" class="max-h-full max-w-full object-contain" loading="lazy" />
                </div>
                <div class="p-4">
                    <h4 class="text-lg font-bold text-[#00ffff] truncate">{{ $game->name }}</h4>
                    <p class="text-gray-400 text-sm mt-1">
                        Platforma: 
                        {{ $game->platforms->count() ? $game->platforms->pluck('name')->implode(', ') : 'Nenurodyta' }}
                    </p>
                    <p class="text-gray-300 mt-1">Kaina: <span class="text-[#00ffff] font-semibold">{{ $game->price }} €</span></p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection