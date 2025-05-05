<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Alanda - Choque de Leyendas') }} - Admin</title>

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
<body class="admin-page">
  <div class="admin-layout">
    <x-admin.header />
    
    <div class="admin-content-wrapper">
      <x-admin.sidebar />
      
      <main class="admin-content">
        <div class="admin-content-inner">
          {{ $slot }}
        </div>
      </main>
    </div>
  </div>

  @stack('scripts')
</body>
</html>