@extends('layouts.app3')

@section('content')
	<br>
	<br>
	<div align='center'>
		<img src='logo_400.png'>
	</div>

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
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

                <a href="{{ url('/password/reset') }}">Forgot password</a>

            </form>
					
            <p class="m-t"> <small>{{ env('APPLICATION_NAME') }} &copy; 2016</small> </p>

        </div>
    </div>

@endsection
