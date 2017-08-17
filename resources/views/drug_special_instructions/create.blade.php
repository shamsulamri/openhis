@extends('layouts.app')

@section('content')
<h1>
New Drug Special Instruction
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_special_instruction, ['url'=>'drug_special_instructions', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('special_code')) has-error @endif'>
        <label for='special_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('special_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('special_code')) <p class="help-block">{{ $errors->first('special_code') }}</p> @endif
        </div>
    </div>    
    
	@include('drug_special_instructions.drug_special_instruction')
{{ Form::close() }}

@endsection
