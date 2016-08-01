@extends('layouts.app')

@section('content')
<h1>
New Drug
</h1>
@include('common.errors')
<br>
{{ Form::model($drug, ['url'=>'drugs', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('drug_code')) has-error @endif'>
        <label for='drug_code' class='col-sm-3 control-label'>drug_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('drug_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('drug_code')) <p class="help-block">{{ $errors->first('drug_code') }}</p> @endif
        </div>
    </div>    
    
	@include('drugs.drug')
{{ Form::close() }}

@endsection
