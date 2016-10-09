@extends('layouts.app')

@section('content')
<h1>
New Sponsor
</h1>
@include('common.errors')
<br>
{{ Form::model($sponsor, ['url'=>'sponsors', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('sponsor_code')) has-error @endif'>
        <label for='sponsor_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('sponsor_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('sponsor_code')) <p class="help-block">{{ $errors->first('sponsor_code') }}</p> @endif
        </div>
    </div>    
    
	@include('sponsors.sponsor')
{{ Form::close() }}

@endsection
