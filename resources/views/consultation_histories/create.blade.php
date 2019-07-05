@extends('layouts.app')

@section('content')
<h1>
New Consultation History
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation_history, ['url'=>'consultation_histories', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_histories.consultation_history')
{{ Form::close() }}

@endsection
