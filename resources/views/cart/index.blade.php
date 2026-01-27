@extends('layouts.app')

@section('title', 'Krepšelis')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h2 class="text-3xl font-bold mb-6 text-[#00ffff]">🛒 Tavo krepšelis</h2>
    @if($cartProducts->count())
        <ul class="mb-6">
            @foreach($cartProducts as $product)
                <li class="flex justify-between py-2 border-b border-[#222] items-center">
                    <span>{{ $product->name }}</span>
                    <div class="flex items-center gap-4">
                        <span class="text-[#00ffff] font-semibold">{{ $product->price }} €</span>
                        <form action="{{ route('cart.remove', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Pašalinti</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="flex justify-between items-center text-xl font-bold mb-6">
            <span>Iš viso:</span>
            <span class="text-[#00ffff]">{{ number_format($total, 2) }} €</span>
        </div>
        <form method="POST" action="{{ route('cart.checkout') }}">
            @csrf
            <button type="submit" class="px-6 py-2 bg-[#00ffff] text-black rounded hover:bg-cyan-400 font-semibold transition">Nusipirkti</button>
        </form>
    @else
        <p class="text-gray-400">Krepšelis tuščias.</p>
    @endif
</div>
@endsection