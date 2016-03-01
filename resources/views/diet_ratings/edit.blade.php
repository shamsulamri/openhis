@extends('layouts.app')

@section('content')
<h1>
Edit Diet Rating
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_rating, ['route'=>['diet_ratings.update',$diet_rating->rate_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>rate_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('rate_code', $diet_rating->rate_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diet_ratings.diet_rating')
{{ Form::close() }}

@endsection
