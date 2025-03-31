<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <div>
            <div>
              @if (session('error'))
                <div class="auth-error">
                  {{ session('error') }}
                </div>
              @endif
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
