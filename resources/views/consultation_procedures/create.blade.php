@extends('layouts.app')

@section('content')
<h1>
New Consultation Procedure
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation_procedure, ['url'=>'consultation_procedures', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_procedures.consultation_procedure')
{{ Form::close() }}

@endsection
