@extends('layouts.app')

@section('content')
    <div class="profile box">
        <div class="title">
            <h1>Profil użytkownika</h1>
        </div>
        <div class="content">
            <div class="user">
                <div class="notification">
                    @if (session('success'))
                        <p style="color: green;">{{ session('success') }}</p>
                    @endif
                </div>
                <div class="user-data">
                    <p>Nazwa: {{ $user->name }}</p>
                    <p>Email: {{ $user->email }}</p>
                </div>
                <hr>
                <div class="user-edit">
                    <div class="password-form">
                        <form method="POST" action="{{ route('profile.update-password') }}">
                            @csrf
                            <section>
                                <label>Podaj obecne hasło</label>
                                <input type="password" name="old_password">
                            </section>
                            <section>
                                <label>Podaj nowe hasło</label>
                                <input type="password" name="new_password">
                            </section>
                            <section>
                                <label>Powtórz nowe hasło</label>
                                <input type="password" name="new_password_confirmation">
                            </section>
                            @if ($errors->any())
                                <div class="errors">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>
                                                <p style="color: red;">{{ $error }}</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <button type="submit">Zmień hasło</button>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="delete">Wyloguj się</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection