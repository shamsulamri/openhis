@extends('layouts.app')

@section('content')
<h1>
New Admission
</h1>
@include('common.errors')
<br>
{{ Form::model($admission, ['url'=>'admissions', 'class'=>'form-horizontal']) }} 
    
	@include('admissions.admission')
{{ Form::close() }}

@endsection
