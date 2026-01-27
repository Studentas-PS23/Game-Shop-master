@extends('layouts.app')

@section('title', 'Mano paskyra')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row font-orbitron bg-[#0d0d0d] text-[#00ffff]">
    <!-- Sidebar -->
    <aside class="w-full lg:w-1/4 bg-[#181818] p-6 space-y-4 border-r border-[#2c2c2c]">
        <h2 class="text-3xl font-bold text-[#00ffff] mb-8 tracking-wide">🎮 GameShop</h2>
        <nav class="space-y-3">
            <a href="#" class="block px-4 py-2 rounded-xl bg-[#0d0d0d] hover:bg-[#00ffff] hover:text-black transition">👤 Profilis</a>
            <a href="#" class="block px-4 py-2 rounded-xl bg-[#0d0d0d] hover:bg-[#00ffff] hover:text-black transition">💳 Mokėjimai</a>
            <a href="#" class="block px-4 py-2 rounded-xl bg-[#0d0d0d] hover:bg-[#00ffff] hover:text-black transition">📬 Adresai</a>
        </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8 space-y-8">
        <!-- Vartotojo informacija -->
        <section class="bg-[#181818] rounded-2xl p-8 shadow-xl border border-[#2c2c2c]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-3xl font-bold tracking-wide">👤 Mano paskyra</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-lg">
                <div class="space-y-2">
                    <p><span class="font-semibold">Vardas:</span> {{ $user->name }}</p>
                    <p><span class="font-semibold">El. paštas:</span> {{ $user->email }}</p>
                </div>
                <div class="space-y-2">
                    <p><span class="font-semibold">Registracijos data:</span> {{ $user->created_at->format('Y-m-d') }}</p>
                    <p><span class="font-semibold">Nupirktų produktų skaičius:</span> {{ count($purchasedProducts) }}</p>
                </div>
            </div>
            <div class="mt-8 text-left">
                <button onclick="document.getElementById('edit-profile-modal').classList.remove('hidden')" class="px-6 py-3 bg-[#00ffff] hover:bg-cyan-400 text-black font-bold rounded-xl transition duration-300">
                    Redaguoti profilį
                </button>
            </div>
        </section>

        <!-- Krepšelis -->
        <section class="bg-[#181818] rounded-2xl p-8 shadow-lg border border-[#2c2c2c]">
            <h4 class="text-2xl font-semibold mb-4 border-b border-[#333] pb-2">🛒 Krepšelis</h4>
            @if(count($cartProducts))
                <ul class="list-disc pl-6 text-[#00ffff]">
                    @foreach($cartProducts as $product)
                        @if($product)
                            <li>{{ $product->name }} – {{ $product->price }} €</li>
                        @endif
                    @endforeach
                </ul>
            @else
                <p class="text-[#00ffff] opacity-70 italic">Krepšelis tuščias.</p>
            @endif
        </section>

        <!-- Nusipirkti produktai -->
        <div class="bg-[#181818] rounded-lg p-6 shadow mt-8">
            <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">🎮 Neseniai nusipirkti produktai</h3>
            @if($purchasedProducts->count())
                <ul>
                    @foreach($purchasedProducts as $item)
                        <li class="flex justify-between py-2 border-b border-[#222]">
                            <span>{{ $item->game->name }}</span>
                            <a href="{{ asset('storage/' . $item->pdf_path) }}"
                               class="text-[#00ffff] underline font-semibold"
                               download>
                               Parsisiųsti PDF
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-gray-400">Nėra nupirktų produktų.</div>
            @endif
        </div>
    </main>
</div>

<!-- PROFILIO REDAGAVIMO MODALAS -->
<div id="edit-profile-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
    <div class="bg-[#181818] rounded-2xl p-8 w-full max-w-md border border-[#2c2c2c] relative">
        <button onclick="document.getElementById('edit-profile-modal').classList.add('hidden')" class="absolute top-2 right-2 text-[#00ffff] text-2xl font-bold">&times;</button>
        <h3 class="text-2xl font-bold mb-6 text-[#00ffff]">Redaguoti profilį</h3>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block mb-2 text-cyan-400">Vardas</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}" class="w-full p-3 rounded bg-[#222] border border-[#333] text-white focus:ring-[#00ffff] focus:border-[#00ffff]">
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-cyan-400">Naujas slaptažodis</label>
                <input type="password" name="password" id="password" class="w-full p-3 rounded bg-[#222] border border-[#333] text-white focus:ring-[#00ffff] focus:border-[#00ffff]">
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block mb-2 text-cyan-400">Pakartokite slaptažodį</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-3 rounded bg-[#222] border border-[#333] text-white focus:ring-[#00ffff] focus:border-[#00ffff]">
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="document.getElementById('edit-profile-modal').classList.add('hidden')" class="mr-4 px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">Atšaukti</button>
                <button type="submit" class="px-4 py-2 bg-[#00ffff] text-black rounded hover:bg-cyan-400 font-semibold transition">Išsaugoti</button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div 
        id="toast-success"
        class="fixed bottom-6 right-6 z-50 flex items-center w-auto max-w-xs p-4 text-green-400 bg-[#181818] rounded-lg shadow-lg border border-green-400 animate-fade-in"
        role="alert"
    >
        <svg class="w-6 h-6 mr-2 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(function() {
            const toast = document.getElementById('toast-success');
            if (toast) toast.style.display = 'none';
        }, 3000);
    </script>
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .animate-fade-in {
            animation: fade-in 0.5s;
        }
    </style>
@endif
@endsection