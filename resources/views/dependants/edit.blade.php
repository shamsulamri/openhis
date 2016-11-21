@extends('layouts.app2')

@section('content')
<h3>
Edit Dependant
</h3>

<br>
{{ Form::model($dependant, ['route'=>['dependants.update',$dependant->patient_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('dependants.dependant')
{{ Form::close() }}

@endsection
