@extends('layouts.app')

@section('content')
<h1>
New Drug Dosage
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_dosage, ['url'=>'drug_dosages', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('dosage_code')) has-error @endif'>
        <label for='dosage_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('dosage_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('dosage_code')) <p class="help-block">{{ $errors->first('dosage_code') }}</p> @endif
        </div>
    </div>    
    
	@include('drug_dosages.drug_dosage')
{{ Form::close() }}

@endsection
