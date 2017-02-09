@extends('layouts.app')

@section('content')
<h1>
New Store Authorization
</h1>

<br>
{{ Form::model($store_authorization, ['url'=>'store_authorizations', 'class'=>'form-horizontal']) }} 
    
	@include('store_authorizations.store_authorization')
{{ Form::close() }}

@endsection
