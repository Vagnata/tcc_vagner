@extends('layouts/sappr_base')

@section('custom-css')
    <link href="{{ asset('css/signin.css') }}" rel="stylesheet">
@stop

@section('base-content')
    <form class="form-signin border" action="{{ url('login') }}" method="post">
        {!! csrf_field() !!}
        <h1 style="text-align: center">SAPPR</h1>
        @if ($errors->has('email') || $errors->has('password'))
            <div class="alert alert-warning text-center" role="alert">
                Usuário ou senha incorretos. Tente outra vez.
            </div>
        @endif
        @if (isset($message))
            <div class="alert alert-warning text-center" role="alert">
                {{$message}}
            </div>
        @endif
        <div class="mb-5">
            <label for="inputEmail" class="sr-only">Email address</label>
            <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email" required
                   autofocus>
        </div>
        <div>
            <label for="inputPassword" class="sr-only">Password</label>
            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Senha" required>
        </div>
        <div class="checkbox mb-1">
            <label>
                <a href="{{route('show-register')}}"> Novo usuário</a>
            </label>
        </div>
        <div class="checkbox mb-1">
            <label class="left">
                <a href="{{route('welcome')}}"> Voltar</a>
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
    </form>

@stop
