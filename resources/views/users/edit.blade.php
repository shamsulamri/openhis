@extends('layouts.app')

@section('content')
<h1>
Edit User
</h1>

<br>
{{ Form::model($user, ['route'=>['users.update',$user->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('users.user')
{{ Form::close() }}

@endsection
