<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Survey App')</title>
    <!-- Include CSS -->


    <style>


        header {
            color: #fff;
            padding: 15px 0;
        }


        input{
  background-color: none;
  border: none;
  border-bottom: 1px solid black;
  background: transparent;
}
    </style>
    <link href="{{ asset('css/survey.css') }}" rel="stylesheet">
</head>
<body>
    <main class="container mt-4">
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} SEIDR Dynamics. All rights reserved.</p>
    </footer>
    @yield('scripts')
</body>
</html>
