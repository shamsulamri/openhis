@extends('layouts.app')

@section('content')
@include('patients.id')
<div class='page-header'>
		<h2>New Admission</h2>
</div>
@include('common.errors')
<br>
{{ Form::model($admission, ['url'=>'admissions', 'class'=>'form-horizontal']) }} 
    
	@include('admissions.admission')
{{ Form::close() }}

@endsection
