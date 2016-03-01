@extends('layouts.app')

@section('content')
<h1>
Edit Consultation
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation, ['route'=>['consultations.update',$consultation->consultation_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('consultations.consultation')
{{ Form::close() }}

@endsection
