@extends('layouts.app')

@section('content')
    <div class="register box">
        <div class="title">
            <h1>Rejestracja</h1>
        </div>
        <div class="content">
            <div class="login-register-box">
                <div class="form-register">
                    <form method="POST" action="{{ route('auth.store') }}">
                        @csrf
                        <section>
                            <label for="name">Nazwa użytkownika:</label> <br>
                            <input name="name" type="text" value="{{ old('name') }}">
                        </section>

                        <section>
                            <label for=" email">Adres email:</label> <br>
                            <input name="email" type="email" value="{{ old('email') }}">
                        </section>

                        <section>
                            <label for="password">Hasło:</label> <br>
                            <input name="password" type="password">
                        </section>

                        <section>
                            <label for="password_confirmation">Powtórz hasło:</label> <br>
                            <input name="password_confirmation" type="password">
                        </section>

                        <button type="submit">Zarejestruj się</button>
                    </form>

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
                </div>
            </div>
        </div>
    </div>
@endsection