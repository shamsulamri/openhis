@extends('layouts.app')

@section('content')
<h1>
New Drug
</h1>
@include('common.errors')
<br>
{{ Form::model($drug, ['url'=>'drugs', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('drug_code')) has-error @endif'>
        <label for='drug_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('drug_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'10.0']) }}
            @if ($errors->has('drug_code')) <p class="help-block">{{ $errors->first('drug_code') }}</p> @endif
        </div>
    </div>    
    
	@include('drugs.drug')
{{ Form::close() }}

@endsection
