@extends('layouts.app2')

@section('content')
<h1>
Edit Dependant
</h1>
@include('common.errors')
<br>
{{ Form::model($dependant, ['route'=>['dependants.update',$dependant->patient_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('dependants.dependant')
{{ Form::close() }}

@endsection
