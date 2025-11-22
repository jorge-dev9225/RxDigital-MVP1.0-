<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} – Recetas médicas digitales seguras</title>


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 sm:px-0 bg-gray-100 pb-12">
            <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
                @isset($logo)
                    {{-- Logo personalizado pasado desde la vista --}}
                    {{ $logo }}
                @else
                    {{-- Logo por defecto (Laravel u otro) --}}
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                @endisset
            </div>
        
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-xl sm:rounded-lg  ">
                {{ $slot }}
            </div>
        </div>     
        <footer class="fixed bottom-0 left-0 w-full border-t border-gray-200 bg-gray-50 py-2 z-20">
            <div class="max-w-7xl mx-auto px-4 text-center text-[11px] text-gray-500">
                <span>RxDigital – Recetas médicas digitales seguras.</span>
                <span class="mx-2">|</span>
                <span>App creada en 2025 por <strong>JM Developers</strong>.</span>
                <span class="mx-2">|</span>
                <span>&copy; {{ date('Y') }} Todos los derechos reservados.</span>
                <span class="mx-2">|</span>
                <span>Wsp: +54 9 2262 374751</span>
                <span class="mx-2">|</span>
                <span>Email: jorgemonter456@gmail.com</span>
            </div>
        </footer>   
    </body>
</html>
