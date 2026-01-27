@extends('layouts.app')

@section('title', 'Nusipirkti produktai')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h2 class="text-3xl font-bold mb-6 text-[#00ffff]">Nusipirkti produktai</h2>
    <ul>
        @foreach($products as $item)
            php artisan migrate:fresh            <li>
                <span>{{ $item->game->name }}</span>
                <a href="{{ asset('storage/' . $item->pdf_path) }}"
                   class="text-[#00ffff] underline font-semibold"
                   download>
                   Parsisiųsti PDF
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection