@extends('layouts.app')

@section('content')
<h1>
New Discharge Summary
</h1>
@include('common.errors')
<br>
{{ Form::model($discharge_summary, ['url'=>'discharge_summaries', 'class'=>'form-horizontal']) }} 
    
	@include('discharge_summaries.discharge_summary')
{{ Form::close() }}

@endsection
