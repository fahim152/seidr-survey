<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Survey App')</title>
    <!-- Include CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        header {
            background: #007bff;
            color: #fff;
            padding: 15px 0;
            text-align: center;
        }

        footer {
            background: #f1f1f1;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>SEIDR Dynamics</h1>
    </header>

    <main class="container mt-4">
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} SEIDR Dynamics. All rights reserved.</p>
    </footer>
</body>
</html>
