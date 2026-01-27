@extends('layouts.app')

@section('title', $game->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex flex-col md:flex-row gap-8">
        <div class="md:w-2/3">
            @if(!empty($game->image))
                <img
                    src="{{ asset($game->image) }}"
                    alt="{{ $game->name }}"
                    class="w-full max-h-[500px] object-cover rounded shadow mb-6"
                />
            @endif

            <h1 class="text-3xl font-bold mb-4 text-[#00ffff]">{{ $game->name }}</h1>

            <div class="mb-4 text-gray-300 leading-relaxed">
                @if(!empty($description))
                    {!! $description !!}
                @else
                    <p>Aprašymo nėra.</p>
                @endif
            </div>
        </div>

        <div class="md:w-1/3 bg-[#181818] rounded-lg p-6 shadow flex flex-col gap-4">
            <div>
                <span class="text-gray-400">Platformos:</span>
                <div class="text-white font-semibold">
                    {{ !empty($platforms) && count($platforms) ? implode(', ', $platforms) : 'Nenurodyta' }}
                </div>
            </div>

            <div>
                <span class="text-gray-400">Žanrai:</span>
                <div class="text-white font-semibold">
                    {{ !empty($genres) && count($genres) ? implode(', ', $genres) : 'Nenurodyta' }}
                </div>
            </div>

            <div>
                <span class="text-gray-400">Kaina:</span>
                <div class="text-[#00ffff] font-bold text-xl">{{ $game->price }} €</div>
            </div>

            <form method="POST" action="{{ route('cart.add', $game->id) }}" class="mt-4">
                @csrf
                <button
                    type="submit"
                    class="w-full py-3 bg-[#00ffff] text-black font-bold rounded hover:bg-cyan-400 transition"
                >
                    Įdėti į krepšelį
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
