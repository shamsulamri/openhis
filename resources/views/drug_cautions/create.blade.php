@extends('layouts.app')

@section('content')
<h1>
New Drug Caution
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_caution, ['url'=>'drug_cautions', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('caution_code')) has-error @endif'>
        <label for='caution_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('caution_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('caution_code')) <p class="help-block">{{ $errors->first('caution_code') }}</p> @endif
        </div>
    </div>    
    
	@include('drug_cautions.drug_caution')
{{ Form::close() }}

@endsection
