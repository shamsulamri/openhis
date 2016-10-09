@extends('layouts.app')

@section('content')
<h1>
Edit Block Date
</h1>
@include('common.errors')
<br>
{{ Form::model($block_date, ['route'=>['block_dates.update',$block_date->block_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('block_code', $block_date->block_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('block_dates.block_date')
{{ Form::close() }}

@endsection
