@extends('layouts.app')

@section('content')
<h1>
Edit Patient Mrn
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_mrn, ['route'=>['patient_mrns.update',$patient_mrn->mrn_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('patient_mrns.patient_mrn')
{{ Form::close() }}

@endsection
