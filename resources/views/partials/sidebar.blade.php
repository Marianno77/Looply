<div class="sidebar" id="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Looply" width="70">
        <h2>Looply</h2>
    </div>
    <div class="list">
        <ul>
            <li><a href="{{ route('home') }}"><img src="{{ asset('images/home.png') }}" width="30">Strona główna</a>
            </li>
            @auth
                <li><a href="{{ route('calendar') }}"><img src="{{ asset('images/calendar.png') }}" width="30">Kalendarz</a>
                </li>
                <li><a href="{{ url('/tasks') }}"><img src="{{ asset('images/clipboard.png') }}" width="30">Twoje
                        zadania</a></li>
                <li><a href="{{ route('results') }}"><img src="{{ asset('images/economy.png') }}" width="35">Twoje
                        wyniki</a></li>
                <li><a href="{{ route('profile') }}"><img src="{{ asset('images/user.png') }}" width="30">Twój profil</a>
                </li>
            @endauth

            @guest
                <li><a href="{{ route('login') }}"><img src="{{ asset('images/enter.png') }}" width="30">Zaloguj się</a>
                </li>
                <li><a href="{{ route('register') }}"><img src="{{ asset('images/add.png') }}" width="30">Zarejestruj
                        się</a></li>
            @endguest
        </ul>
        <div class="signature">
            <p>Strona wykonana przez:</p>
            <p>Mariusza Bienasz</p>
        </div>
    </div>
</div>