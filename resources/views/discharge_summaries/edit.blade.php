@extends('layouts.app')

@section('content')
<h1>
Edit Discharge Summary
</h1>
@include('common.errors')
<br>
{{ Form::model($discharge_summary, ['route'=>['discharge_summaries.update',$discharge_summary->encounter_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('discharge_summaries.discharge_summary')
{{ Form::close() }}

@endsection
