<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" href="{{ asset('images/logo-white.png') }}">
    <title>Looply</title>
</head>

<body>
    <button onclick="toggleMenu()" id="hamburger"><img src="{{ asset('images/hamburger.png') }}" alt="hamburger"
            width="40"></button>
    <div class="container">
        <div class="menu" id="menu">
            @include('partials.sidebar')
        </div>
        <div class="main" id="main">
            @yield('content')
        </div>
    </div>
</body>

<script>
    const toggleMenu = () => {
        const menu = document.getElementById('menu');
        menu.classList.toggle('hidden');
    }
</script>

</html>