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
    <x-application-logo />
    <x-game-dice size="lg" animation="infinite-spin" color1="#FFD976" color2="#FFD976" color3="#FFD976"/>
    <x-button :route="route('login')" filled="true">Acceder</x-button>
  </div>
</body>
</html>