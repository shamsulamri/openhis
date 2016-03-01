@extends('layouts.app')

@section('content')
<h1>
Edit Unit Measure
</h1>
@include('common.errors')
<br>
{{ Form::model($unit_measure, ['route'=>['unit_measures.update',$unit_measure->unit_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>unit_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('unit_code', $unit_measure->unit_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('unit_measures.unit_measure')
{{ Form::close() }}

@endsection
