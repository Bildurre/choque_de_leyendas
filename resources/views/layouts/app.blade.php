<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Alanda: Choque de Leyendas') }}</title>

    <!-- Scripts -->
    @vite(['resources/scss/_app.scss', 'resources/js/app.js'])
  </head>
  <body class="font-sans antialiased">
    <div class="admin-layout">
      <!-- Page Heading -->
      <header class="admin-header">
        <div class="header-container">
          <div class="header-logo">
            <x-application-logo class="logo-small" />
            <span class="header-title">Alanda</span>
          </div>
          
          <div class="header-actions">
            <span class="welcome-text">Bienvenido, {{ Auth::user()->name }}</span>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="admin-main">
        {{ $slot }}
      </main>
    </div>
  </body>
</html>