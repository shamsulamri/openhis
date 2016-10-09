@extends('layouts.app')

@section('content')
<h1>
Edit Diet Meal
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_meal, ['route'=>['diet_meals.update',$diet_meal->meal_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('meal_code', $diet_meal->meal_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diet_meals.diet_meal')
{{ Form::close() }}

@endsection
