@extends('layouts.app2')

@section('content')
<h1>
New Dependant
</h1>
@include('common.errors')
<br>
{{ Form::model($dependant, ['url'=>'dependants', 'class'=>'form-horizontal']) }} 
    
	@include('dependants.dependant')
{{ Form::close() }}

@endsection
