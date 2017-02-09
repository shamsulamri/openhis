@extends('layouts.app')

@section('content')
<h1>
Edit Store Authorization
</h1>

<br>
{{ Form::model($store_authorization, ['route'=>['store_authorizations.update',$store_authorization->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('store_authorizations.store_authorization')
{{ Form::close() }}

@endsection
