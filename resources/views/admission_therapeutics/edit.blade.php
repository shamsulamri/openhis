@extends('layouts.app')

@section('content')
<h1>
Edit Admission Therapeutic
</h1>
@include('common.errors')
<br>
{{ Form::model($admission_therapeutic, ['route'=>['admission_therapeutics.update',$admission_therapeutic->admission_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('admission_therapeutics.admission_therapeutic')
{{ Form::close() }}

@endsection
