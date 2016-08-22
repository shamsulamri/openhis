@extends('layouts.app')

@section('content')
<h1>
New City
</h1>
@include('common.errors')
<br>
{{ Form::model($city, ['url'=>'cities', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('city_code')) has-error @endif'>
        <label for='city_code' class='col-sm-2 control-label'>city_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('city_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('city_code')) <p class="help-block">{{ $errors->first('city_code') }}</p> @endif
        </div>
    </div>    
    
	@include('cities.city')
{{ Form::close() }}

@endsection
