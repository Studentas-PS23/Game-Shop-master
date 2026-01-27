@extends('layouts.app')

@section('title', 'Žaidimų paieška')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-semibold mb-6 text-white">Paieškos rezultatai: 
        <span class="text-cyan-400">"{{ $query }}"</span>
    </h2>

    @if($games->count())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($games as $game)
                <div class="bg-[#181818] rounded-2xl shadow-md hover:shadow-lg transition duration-300 p-6 flex flex-col md:flex-row gap-6">
                    @if(!empty($game->image))
                        <div class="md:w-1/3">
                            <img src="{{ asset($game->image) }}" alt="{{ $game->name }}" class="w-full object-cover rounded-lg max-h-48" />
                        </div>
                    @endif
                    <div class="md:w-2/3 flex flex-col justify-between">
                        <div>
                            <a href="{{ route('games.show', $game->slug) }}">
                                <h3 class="text-2xl font-bold text-[#00ffff] hover:text-cyan-400 transition duration-200">
                                    {{ $game->name }}
                                </h3>
                            </a>
                            {{-- Galima rodyti trumpą aprašymą, jei yra --}}
                            @if(!empty($game->description))
                                <p class="mt-2 text-gray-400 text-sm">
                                    {{ Str::limit(strip_tags($game->description), 120) }}
                                </p>
                            @endif
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3 text-sm text-gray-300">
                            <div>
                                <span class="text-gray-400">Kaina: </span>
                                <span class="text-[#00ffff] font-semibold">{{ $game->price }} €</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Platformos: </span>
                                <span>
                                    {{ $game->platforms && $game->platforms->count() ? $game->platforms->pluck('name')->implode(', ') : 'Nenurodyta' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-400">Žanrai: </span>
                                <span>
                                    {{ $game->genres && $game->genres->count() ? $game->genres->pluck('name')->implode(', ') : 'Nenurodyta' }}
                                </span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('cart.add', $game->id) }}" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="w-full py-2 bg-[#00ffff] text-black font-bold rounded hover:bg-cyan-400 transition">
                                Įdėti į krepšelį
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-lg mt-4">
            <p><strong>Oops!</strong> Nerasta žaidimų pagal užklausą <span class="italic">"{{ $query }}"</span>.</p>
        </div>
    @endif
</div>
@endsection
