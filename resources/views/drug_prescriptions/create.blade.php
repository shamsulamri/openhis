@extends('layouts.app')

@section('content')
<h1>
New Drug Prescription
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_prescription, ['url'=>'drug_prescriptions', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group  @if ($errors->has('drug_code')) has-error @endif'>
        {{ Form::label('drug_code', 'Drug',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('drug_code', $drug,$drug_prescription->drug_code, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('drug_code')) <p class="help-block">{{ $errors->first('drug_code') }}</p> @endif
        </div>
    </div>

	@include('drug_prescriptions.drug_prescription')
{{ Form::close() }}

@endsection
