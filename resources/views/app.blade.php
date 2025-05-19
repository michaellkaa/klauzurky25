<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Moje aplikace</title>

    {{-- Vite CSS --}}
    @vite('resources/css/app.css')
  </head>
  <body class="antialiased bg-gray-100 text-gray-900">

<<<<<<< Updated upstream
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
=======
    {{-- Vue mount point --}}
    <div id="app"></div>

    {{-- Vite JS --}}
    @vite('resources/js/app.js')
  </body>
>>>>>>> Stashed changes
</html>
