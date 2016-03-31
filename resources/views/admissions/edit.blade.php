@extends('layouts.app')

@section('content')
@include('patients.id')
<h2>
Edit Admission
</h2>
@include('common.errors')
<br>
{{ Form::model($admission, ['route'=>['admissions.update',$admission->admission_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('admissions.admission')
{{ Form::close() }}

@endsection
