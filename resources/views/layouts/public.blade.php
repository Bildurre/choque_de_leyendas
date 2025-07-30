<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Dynamic title --}}
  <title>{{ $title ?? config('app.name', 'Alanda - Choque de Leyendas') }}</title>
  
  {{-- Dynamic meta description --}}
  <meta name="description" content="{{ $metaDescription ?? 'Explora el mundo de Alanda - Choque de Leyendas. Descubre todas las cartas, héroes y facciones del juego de cartas estratégico.' }}">
  
  {{-- Open Graph tags for social media --}}
  <meta property="og:title" content="{{ $ogTitle ?? $title ?? config('app.name') }}">
  <meta property="og:description" content="{{ $ogDescription ?? $metaDescription ?? 'Explora el mundo de Alanda - Choque de Leyendas' }}">
  <meta property="og:type" content="{{ $ogType ?? 'website' }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ $ogImage ?? asset('storage/images/logos/full_logo_'.strtolower(app()->getLocale()).'.png')" }}">
  
  {{-- Additional meta tags --}}
  {{ $metaTags ?? '' }}

  <!-- Theme Script (debe ir antes de los estilos) -->
  <script>
    // Detectar y aplicar el tema inmediatamente para evitar parpadeo
    (function() {
      var theme = localStorage.getItem('theme');
      if (!theme) {
        theme = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
      }
      document.documentElement.setAttribute('data-theme', theme);
    })();
  </script>

  <!-- JavaScript Translations -->
  <x-js-translations />

  <!-- Scripts and Styles -->
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="page-{{ str_replace('.', '-', Route::currentRouteName()) }}">
  <x-public.header />

  <main class="content-container">
    <x-notifications />
    {{ $slot }}
  </main>

  <x-public.footer />

  @stack('scripts')
</body>
</html>