@extends('layouts.app')

@section('content')
<h1>
Edit Diet Period
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_period, ['route'=>['diet_periods.update',$diet_period->period_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('period_code', $diet_period->period_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diet_periods.diet_period')
{{ Form::close() }}

@endsection
