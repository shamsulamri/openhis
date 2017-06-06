@extends('layouts.app')

@section('content')
<h1>
New Diet Therapeutic
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_therapeutic, ['url'=>'diet_therapeutics', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('therapeutic_code')) has-error @endif'>
        <label for='therapeutic_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('therapeutic_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('therapeutic_code')) <p class="help-block">{{ $errors->first('therapeutic_code') }}</p> @endif
        </div>
    </div>    
    
	@include('diet_therapeutics.diet_therapeutic')
{{ Form::close() }}

@endsection
