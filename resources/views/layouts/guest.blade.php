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
                <a href="/">
                    <x-application-logo gradient="true" 
                    gradient-start="#3d3df5" 
                    gradient-end="#f53d3d"
                    stroke="#000000" 
                    stroke-width="2" />
                </a>
            </div>

            <div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
