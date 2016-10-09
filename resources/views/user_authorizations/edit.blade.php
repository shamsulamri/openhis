@extends('layouts.app')

@section('content')
<h1>
Edit User Authorization
</h1>
@include('common.errors')
{{ Form::model($user_authorization, ['route'=>['user_authorizations.update',$user_authorization->author_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('user_authorizations.user_authorization')
{{ Form::close() }}

@endsection
