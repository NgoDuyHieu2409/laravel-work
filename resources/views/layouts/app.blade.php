<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('page_title', setting('site.title') . " - " . setting('site.description'))</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">

        @livewireStyles
        <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('template/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('template/css/custom.css') }}">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        @if (isset($header))
                            <header class="bg-white shadow">
                                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                    {{ $header }}
                                </div>
                            </header>
                        @endif
                    </div>
                </div>

                <div class="content">
                    <div class="container-fluid">
                    {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @include("homes.layouts.footer")

        @stack('modals')

        @stack('scripts')
        <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE -->
        <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
        <script src="{{ asset('template/dist/js/adminlte.js') }}"></script>
    </body>
</html>
