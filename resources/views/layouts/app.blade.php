<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | RSU St. Elisabeth Purwokerto</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="antialiased font-sans bg-light-gray">
    @include('layouts.nav')
    <main>
        @yield('content')
    </main>
    @include('layouts.footer')
    @yield('javascript')
</body>
</html>