@extends('layouts.app')

@section('content')
<h1>
New Diet Rating
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_rating, ['url'=>'diet_ratings', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('rate_code')) has-error @endif'>
        <label for='rate_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('rate_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('rate_code')) <p class="help-block">{{ $errors->first('rate_code') }}</p> @endif
        </div>
    </div>    
    
	@include('diet_ratings.diet_rating')
{{ Form::close() }}

@endsection
