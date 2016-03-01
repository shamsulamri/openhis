@extends('layouts.app')

@section('content')
<h1>
Edit Admission
</h1>
@include('common.errors')
<br>
{{ Form::model($admission, ['route'=>['admissions.update',$admission->admission_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('admissions.admission')
{{ Form::close() }}

@endsection
