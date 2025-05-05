<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Alanda - Choque de Leyendas') }}</title>
  <meta name="description" content="@yield('meta_description', 'Explora el mundo de Alanda - Choque de Leyendas, todas las cartas, heroes y facciones del juego')">

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

  <!-- Scripts and Styles -->
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="page-{{ str_replace('.', '-', Route::currentRouteName()) }}">
  <x-public-header />

  <main class="content-container">
    {{ $slot }}
  </main>

  <x-public-footer />

  @stack('scripts')
</body>
</html>