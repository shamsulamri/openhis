@extends('layouts.app')

@section('content')
<h1>
New Encounter
</h1>
@include('common.errors')
<br>
{{ Form::model($encounter, ['url'=>'encounters', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
{{ Form::close() }}

@endsection
