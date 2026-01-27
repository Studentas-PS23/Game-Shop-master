@extends('layouts.app')

@section('title', 'Registracija')

@section('content')
<div class="max-w-md mx-auto mt-16 bg-[#181818] p-8 rounded-lg shadow-lg border border-[#2c2c2c]">
    <h2 class="text-2xl font-bold mb-6 text-[#00ffff]">Registruotis</h2>
    @if($errors->any())
        <div class="mb-4 text-red-400">
            {{ $errors->first() }}
        </div>
    @endif
    <form method="POST" action="{{ route('register.submit') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block mb-2 text-cyan-400">Vardas</label>
            <input type="text" name="name" id="name" required class="w-full p-3 rounded bg-[#222] border border-[#333] text-white">
        </div>
        <div class="mb-4">
            <label for="email" class="block mb-2 text-cyan-400">El. paštas</label>
            <input type="email" name="email" id="email" required class="w-full p-3 rounded bg-[#222] border border-[#333] text-white">
        </div>
        <div class="mb-4">
            <label for="password" class="block mb-2 text-cyan-400">Slaptažodis</label>
            <input type="password" name="password" id="password" required class="w-full p-3 rounded bg-[#222] border border-[#333] text-white">
        </div>
        <div class="mb-6">
            <label for="password_confirmation" class="block mb-2 text-cyan-400">Pakartokite slaptažodį</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full p-3 rounded bg-[#222] border border-[#333] text-white">
        </div>
        <button type="submit" class="w-full py-3 bg-[#00ffff] text-black font-bold rounded hover:bg-cyan-400 transition">
            Registruotis
        </button>
    </form>
    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-cyan-400 hover:underline">Jau turi paskyrą? Prisijunk</a>
    </div>
</div>
@endsection