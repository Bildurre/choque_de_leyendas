<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Alanda: Choque de Leyendas') }}</title>

  <!-- Scripts -->
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
  <div class="welcome-container">
    <div class="welcome-logo">
      <x-application-logo class="logo-large" />
      <h1 class="welcome-title">
        <x-widgets.game-dice size="xl" class="dice-infinite-spin" />
        ALANDA
      </h1>
      <p class="welcome-subtitle">CHOQUE DE LEYENDAS</p>
      
      <div class="welcome-actions">
        <a href="{{ route('login') }}" class="welcome-button">Acceder</a>
      </div>
    </div>
  </div>
</body>
</html>