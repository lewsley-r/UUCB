@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@900&family=Ubuntu:wght@500&display=swap" rel="stylesheet"> 
<div class="container" style="font-family: 'Ubuntu', sans-serif;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h1>
                        Hello {{ Auth::user()->name }}
                    </h1>
                    <p>
                        {{ __('You are logged in!') }}
                    </p>
                    <a class="navbar-brand" href="{{ url('/frontendHome') }}">
                        Proceed to the platform
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
