@extends('layouts.app')

@section('content')
    <div class="login box">
        <div class="title">
            <h1>Logowanie</h1>
        </div>
        <div class="content">
            <div class="login-register-box">
                <div class="form-login">
                    <form method="POST" action="{{ route('auth.authenticate') }}">
                        @csrf
                        <section>
                            <label for="email">Adres email:</label> <br>
                            <input name="email" type="email" value="{{ old('email') }}">
                        </section>

                        <section>
                            <label for="password">Hasło:</label> <br>
                            <input name="password" type="password">
                        </section>

                        <button type="submit">Zaloguj się</button>
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