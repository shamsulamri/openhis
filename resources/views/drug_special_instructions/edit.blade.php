@extends('layouts.app')

@section('content')
<h1>
Edit Drug Special Instruction
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_special_instruction, ['route'=>['drug_special_instructions.update',$drug_special_instruction->special_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('special_code', $drug_special_instruction->special_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drug_special_instructions.drug_special_instruction')
{{ Form::close() }}

@endsection
