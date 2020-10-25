@extends('layouts.app3')

@section('content')
	<br>
	<br>
	<div align='center'>
		@if (env('APPLICATION_LOGO')) 
		<img width='280' height='300' src='{{ env('APPLICATION_LOGO') }}'>
		@else
		<h1>{{ env('APPLICATION_NAME') }}</h1>
		@endif
	</div>

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
		@if (Session::has('message'))
		<div class='alert alert-success'>
		<h3>
				{{ Session::get('message') }}
		</h3>
		</div>
		@endif
            <p>Enter your username and password to login</p>
            <form class="m-t" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
                <div class="form-group">
                        <input type="text" placeholder='Username' class="form-control" name="username" value="{{ old('username') }}">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

				<!--
                <a href="{{ url('/password/reset') }}">Forgot password</a>
				-->

            </form>
					
            <p class="m-t"> <small><a href='https://iodojo.com'>IODOJO</a></small> </p>

        </div>
    </div>

@endsection
