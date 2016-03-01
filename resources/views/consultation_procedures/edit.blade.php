@extends('layouts.app')

@section('content')
<h1>
Edit Consultation Procedure
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation_procedure, ['route'=>['consultation_procedures.update',$consultation_procedure->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_procedures.consultation_procedure')
{{ Form::close() }}

@endsection
