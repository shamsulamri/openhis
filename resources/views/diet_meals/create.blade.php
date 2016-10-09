@extends('layouts.app')

@section('content')
<h1>
New Diet Meal
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_meal, ['url'=>'diet_meals', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('meal_code')) has-error @endif'>
        <label for='meal_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('meal_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('meal_code')) <p class="help-block">{{ $errors->first('meal_code') }}</p> @endif
        </div>
    </div>    
    
	@include('diet_meals.diet_meal')
{{ Form::close() }}

@endsection
