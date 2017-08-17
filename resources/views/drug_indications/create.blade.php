@extends('layouts.app')

@section('content')
<h1>
New Drug Indication
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_indication, ['url'=>'drug_indications', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('indication_code')) has-error @endif'>
        <label for='indication_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('indication_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('indication_code')) <p class="help-block">{{ $errors->first('indication_code') }}</p> @endif
        </div>
    </div>    
    
	@include('drug_indications.drug_indication')
{{ Form::close() }}

@endsection
