@extends('layouts.app')

@section('content')
<h1>
New User Authorization
</h1>
@include('common.errors')
<br>
{{ Form::model($user_authorization, ['url'=>'user_authorizations', 'class'=>'form-horizontal']) }} 
    
	@include('user_authorizations.user_authorization')
{{ Form::close() }}

@endsection
