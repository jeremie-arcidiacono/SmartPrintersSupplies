<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Import Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

        <!-- Import jQuery -->
        <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

    </head>
    <body class="font-sans antialiased">
        @include('layouts.navigation')
        <div class="min-h-screen bg-gray-100 float-end d-flex flex-column" style="width: 83%">

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="w-50 py-6 px-4 sm:px-6 lg:px-8 float-start">
                    {{ $header }}
                </div>
                @if (isset($addButton))
                <div class="w-25 pt-6 px-4 sm:px-6 lg:px-8 float-end">
                    <button type="button" class="btn btn-success">
                        <i class="bi bi-plus"></i>
                        {{ $addButton }}
                    </button>
                </div>
                @endif
            </header>

            <!-- Page Content -->
            <main class="float-end px-4">
                {{ $slot }}
            </main>

            @include('layouts.footer')
        </div>

        <!-- Import Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    </body>
</html>
