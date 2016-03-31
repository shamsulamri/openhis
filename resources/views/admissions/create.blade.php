@extends('layouts.app')

@section('content')
@include('patients.id')
<div class='page-header'>
		<h1>New Admission</h1>
</div>
@include('common.errors')
<br>
{{ Form::model($admission, ['url'=>'admissions', 'class'=>'form-horizontal']) }} 
    
	@include('admissions.admission')
{{ Form::close() }}

@endsection
