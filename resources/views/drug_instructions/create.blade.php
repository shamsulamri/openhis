@extends('layouts.app')

@section('content')
<h1>
New Drug Instruction
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_instruction, ['url'=>'drug_instructions', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('instruction_code')) has-error @endif'>
        <label for='instruction_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('instruction_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('instruction_code')) <p class="help-block">{{ $errors->first('instruction_code') }}</p> @endif
        </div>
    </div>    
    
	@include('drug_instructions.drug_instruction')
{{ Form::close() }}

@endsection
