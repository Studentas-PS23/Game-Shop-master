@extends('layouts.app')

@section('title', 'Apmokėjimas sėkmingas')

@section('content')
<div class="max-w-xl mx-auto py-10 text-center">
    <h2 class="text-3xl font-bold mb-6 text-[#00ffff]">Apmokėjimas sėkmingas!</h2>
    <p class="text-gray-300 mb-4">Jūsų užsakymas gautas. PDF sąskaita bus prieinama profilyje.</p>
    <a href="{{ route('profile') }}" class="text-[#00ffff] underline font-semibold">Eiti į profilį</a>
</div>
@endsection