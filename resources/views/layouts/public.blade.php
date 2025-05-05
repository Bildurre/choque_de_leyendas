<!-- resources/views/layouts/public.blade.php (updated) -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Alanda - Choque de Leyendas') }}</title>
  <meta name="description" content="@yield('meta_description', 'Explora el mundo de Alanda - Choque de Leyendas, todas las cartas, heroes y facciones del juego')">

  <!-- Theme detector script - loaded separately and early -->
  @vite('resources/js/theme-detector.js')
  
  <!-- Main scripts and styles -->
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="public-layout">
  <x-public-header />

  <main class="content-container">
    {{ $slot }}
  </main>

  <x-public-footer />

  @stack('scripts')
</body>
</html>