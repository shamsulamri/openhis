@extends('layouts.app')

@section('content')
<h1>
Edit User Authorization
</h1>
@include('common.errors')
<br>
{{ Form::model($user_authorization, ['route'=>['user_authorizations.update',$user_authorization->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('user_authorizations.user_authorization')
{{ Form::close() }}

@endsection
