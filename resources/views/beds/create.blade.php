@extends('layouts.app')

@section('content')
<h1>
New Bed
</h1>
@include('common.errors')
<br>
{{ Form::model($bed, ['url'=>'beds', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('bed_code')) has-error @endif'>
        <label for='bed_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bed_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('bed_code')) <p class="help-block">{{ $errors->first('bed_code') }}</p> @endif
        </div>
    </div>    
    
	@include('beds.bed')
{{ Form::close() }}

@endsection
