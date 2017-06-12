@extends('layouts.app')

@section('content')
<h1>
New Admission Therapeutic
</h1>
@include('common.errors')
<br>
{{ Form::model($admission_therapeutic, ['url'=>'admission_therapeutics', 'class'=>'form-horizontal']) }} 
    
	@include('admission_therapeutics.admission_therapeutic')
{{ Form::close() }}

@endsection
