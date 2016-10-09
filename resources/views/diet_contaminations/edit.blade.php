@extends('layouts.app')

@section('content')
<h1>
Edit Diet Contamination
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_contamination, ['route'=>['diet_contaminations.update',$diet_contamination->contamination_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('contamination_code', $diet_contamination->contamination_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diet_contaminations.diet_contamination')
{{ Form::close() }}

@endsection
