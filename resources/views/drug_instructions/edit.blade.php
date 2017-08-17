@extends('layouts.app')

@section('content')
<h1>
Edit Drug Instruction
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_instruction, ['route'=>['drug_instructions.update',$drug_instruction->instruction_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('instruction_code', $drug_instruction->instruction_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drug_instructions.drug_instruction')
{{ Form::close() }}

@endsection
