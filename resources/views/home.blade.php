@extends('layouts.app')

@section('title', 'Žaidimų tinklas | Žaidimų Skelbimų Portalas')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden pt-20 pb-24 text-center">
  <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-pink-500/10 blur-3xl"></div>
  <div class="relative w-full max-w-4xl mx-auto">
    <div id="default-carousel" class="relative w-full" data-carousel="slide">
      <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
        @for($i = 1; $i <= 5; $i++)
        <div class="{{ $i === 1 ? '' : 'hidden' }} duration-700 ease-in-out" data-carousel-item>
          <img src="{{ asset('images/carousel/' . $i . '.jpg') }}" class="absolute block w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="Karuselė {{ $i }}">
        </div>
        @endfor
      </div>
      <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
        @for($i = 0; $i < 5; $i++)
        <button type="button" class="w-3 h-3 rounded-full" data-carousel-slide-to="{{ $i }}"></button>
        @endfor
      </div>
      <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white">
          <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
          </svg>
          <span class="sr-only">Previous</span>
        </span>
      </button>
      <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white">
          <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
          </svg>
          <span class="sr-only">Next</span>
        </span>
      </button>
    </div>
  </div>
</section>

<!-- Nauji žaidimai -->
<section class="max-w-7xl mx-auto px-6 py-12">
  <h2 class="text-2xl font-bold mb-6 text-[#00ffff]">Nauji žaidimai</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
    @foreach($newGames as $game)
    <a href="{{ route('games.show', $game['slug']) }}" class="bg-[#1a1a1a] border border-[#333] rounded-lg overflow-hidden card-hover block hover:shadow-lg transition transform hover:-translate-y-1">
      <div class="w-full h-64 bg-black flex items-center justify-center">
        <img src="{{ asset($game['image']) }}" alt="{{ $game['name'] }}" class="max-h-full max-w-full object-contain" loading="lazy" />
      </div>
      <div class="p-4">
        <h4 class="text-lg font-bold text-[#00ffff] truncate">{{ $game['name'] }}</h4>
        <p class="text-gray-400 text-sm mt-1">Platforma: {{ $game['platform'] }}</p>
        <p class="text-gray-300 mt-1">Kaina: <span class="text-[#00ffff] font-semibold">{{ $game['price'] }} €</span></p>
      </div>
    </a>
    @endforeach
  </div>
    <div class="flex justify-center mt-8">  
<a href="{{ route('games.index') }}" class="px-8 py-3 bg-[#00ffff] text-black font-bold rounded hover:bg-cyan-400 transition">Rodyti viską</a>  </div>
</section>

<!-- Populiariausi žaidimai -->
<section class="max-w-7xl mx-auto px-6 py-12">
  <h2 class="text-2xl font-bold mb-6 text-[#00ffff]">Populiariausi žaidimai</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
    @foreach($popularGames as $game)
    <a href="{{ route('games.show', $game['slug']) }}" class="bg-[#1a1a1a] border border-[#333] rounded-lg overflow-hidden card-hover block hover:shadow-lg transition transform hover:-translate-y-1">
      <div class="w-full h-64 bg-black flex items-center justify-center">
        <img src="{{ asset($game['image']) }}" alt="{{ $game['name'] }}" class="max-h-full max-w-full object-contain" loading="lazy" />
      </div>
      <div class="p-4">
        <h4 class="text-lg font-bold text-[#00ffff] truncate">{{ $game['name'] }}</h4>
        <p class="text-gray-400 text-sm mt-1">Platforma: {{ $game['platform'] }}</p>
        <p class="text-gray-300 mt-1">Kaina: <span class="text-[#00ffff] font-semibold">{{ $game['price'] }} €</span></p>
      </div>
    </a>
    @endforeach
  </div>
    <div class="flex justify-center mt-8">
    <a href="#" class="px-8 py-3 bg-[#00ffff] text-black font-bold rounded hover:bg-cyan-400 transition">Rodyti viską</a>
  </div>
</section>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('dropdown-toggle');
    const menu = document.getElementById('dropdown-menu');

    toggleBtn?.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
  });
</script>
@endpush
