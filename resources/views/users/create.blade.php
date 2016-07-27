@extends('layouts.app')

@section('content')
<h1>
New User
</h1>
@include('common.errors')
<br>
{{ Form::model($user, ['url'=>'users', 'class'=>'form-horizontal']) }} 
    
	@include('users.user')
{{ Form::close() }}

@endsection
