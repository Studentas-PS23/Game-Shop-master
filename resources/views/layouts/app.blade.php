<!DOCTYPE html>
<html lang="lt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Žaidimų tinklas')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Orbitron', sans-serif; }
    .card-hover { transition: transform 0.3s, box-shadow 0.3s; }
    .card-hover:hover { transform: scale(1.03); box-shadow: 0 0 25px rgba(0,255,255,0.5); }
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(30px);}
      to { opacity: 1; transform: translateY(0);}
    }
    .animate-fade-in { animation: fade-in 0.4s; }
  </style>
  @stack('styles')
</head>
<body class="bg-[#0d0d0d] text-white scroll-smooth min-h-screen flex flex-col">
  
  <!-- Header -->
  <header class="bg-gradient-to-r from-[#1a1a1a] to-[#111] shadow-lg p-4 flex flex-col md:flex-row justify-between items-center gap-4 sticky top-0 z-50 border-b border-[#2c2c2c]">
    <div class="flex items-center gap-3">
      <a href="{{ url('/') }}" class="text-3xl font-bold text-[#00ffff] tracking-wide">
        Žaidimų tinklas
      </a>
    </div>
  
    <!-- PAIEŠKOS FORMA -->
    <form action="{{ route('games.search') }}" method="GET" class="max-w-md mx-auto w-full">
      <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Ieškoti</label>
      <div class="relative">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
          <svg class="w-4 h-4 text-cyan-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
          </svg>
        </div>
        <input type="search" name="q" id="default-search"
          class="block w-full p-4 ps-10 text-sm text-cyan-200 border border-[#222] rounded-lg bg-[#181818] focus:ring-[#00ffff] focus:border-[#00ffff] placeholder-cyan-400"
          placeholder="Ieškoti žaidimo..." value="{{ request('q') }}" />
        <button type="submit"
          class="text-black absolute end-2.5 bottom-2.5 bg-[#00ffff] hover:bg-cyan-400 focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-sm px-4 py-2 transition">
          Ieškoti
        </button>
      </div>
    </form>
    <!-- Avataras su dropdown -->
    <div class="relative">
      @guest
        <a href="{{ route('login') }}" class="w-10 h-10 rounded-full cursor-pointer bg-[#222] flex items-center justify-center text-2xl text-[#00ffff]" title="Prisijungti">
          👤
        </a>
      @else
        <button id="avatarButton" type="button" class="w-10 h-10 rounded-full cursor-pointer bg-[#222] flex items-center justify-center text-2xl text-[#00ffff]">
          👤
        </button>
        <!-- Dropdown menu -->
        <div id="userDropdown" class="z-10 hidden absolute right-0 mt-2 bg-[#181818] divide-y divide-[#333] rounded-lg shadow w-44">
          <ul class="py-2 text-sm text-gray-200" aria-labelledby="avatarButton">
            <li>
              <a href="{{ route('profile') }}" class="block px-4 py-2 hover:bg-[#222]">Mano paskyra</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-[#222]">Užsakymai</a>
            </li>
          </ul>
          <div class="py-1">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-[#222]">Atsijungti</button>
            </form>
          </div>
        </div>
      @endguest
    </div>
    <!-- Cart Icon -->
    <a href="{{ route('cart.index') }}" class="relative group ml-4">
      <svg class="w-8 h-8 text-[#00ffff] hover:text-cyan-400 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 0 0 7.5 19h9a2 2 0 0 0 1.85-2.3L17 13M7 13V6a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v7" />
      </svg>
      @if(auth()->check() && auth()->user()->cartProducts->count())
        <span class="absolute -top-2 -right-2 bg-[#00ffff] text-black text-xs font-bold rounded-full px-2 py-0.5">
          {{ auth()->user()->cartProducts->count() }}
        </span>
      @endif
    </a>
  </header>

  <!-- PAGRINDINIS TURINYS -->
  <main class="flex-1">
    @yield('content')
  </main>

  <footer class="bg-[#1a1a1a] rounded-lg shadow-sm w-full mt-8">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
      <div class="sm:flex sm:items-center sm:justify-between">
        <a href="http://127.0.0.1:8000/#" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
          <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">Žaidimų tinklas</span>
        </a>
        <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-300 sm:mb-0">
          <li>
            <a href="#" class="hover:underline me-4 md:me-6">Apie Žaidimų tinklą</a>
          </li>
          <li>
            <a href="#" class="hover:underline me-4 md:me-6">Pagrindinis puslapis</a>
          </li>
          <li>
            <a href="#" class="hover:underline me-4 md:me-6">Pagalba</a>
          </li>
          <li>
            <a href="#" class="hover:underline">Kontaktai</a>
          </li>
        </ul>
      </div>
      <hr class="my-6 border-[#333] sm:mx-auto lg:my-8" />
      <span class="block text-sm text-gray-400 sm:text-center">
        © 2025 <a href="http://127.0.0.1:8000/#" class="hover:underline">Žaidimų tinklas™</a>. Visos teisės saugomos.
      </span>
    </div>
  </footer>

  @if(session('cart_success'))
    <div id="toast"
      class="fixed bottom-6 right-6 bg-[#00ffff] text-black px-6 py-3 rounded shadow-lg z-50 animate-fade-in"
      style="min-width:220px;">
      {{ session('cart_success') }}
    </div>
    <script>
      setTimeout(() => {
        const toast = document.getElementById('toast');
        if (toast) toast.style.display = 'none';
      }, 3000);
    </script>
  @endif

  @stack('scripts')

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const btn = document.getElementById('avatarButton');
      const menu = document.getElementById('userDropdown');
      if (btn && menu) {
        btn.addEventListener('click', function (e) {
          e.stopPropagation();
          menu.classList.toggle('hidden');
        });
        document.addEventListener('click', function (e) {
          if (!menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
          }
        });
        menu.addEventListener('click', function (e) {
          e.stopPropagation();
        });
      }
    });
  </script>
</body>
</html>