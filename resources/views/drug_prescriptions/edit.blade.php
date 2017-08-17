@extends('layouts.app')

@section('content')
<h1>
Edit Drug Prescription
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_prescription, ['route'=>['drug_prescriptions.update',$drug_prescription->prescription_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    <div class='form-group'>
        {{ Form::label('drug', 'Drug',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug', $drug_prescription->product->product_name, ['class'=>'form-control','maxlength'=>'20']) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('drug_code')) has-error @endif'>
        {{ Form::label('drug_code', 'Code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_code', null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('drug_code')) <p class="help-block">{{ $errors->first('drug_code') }}</p> @endif
        </div>
    </div>

	@include('drug_prescriptions.drug_prescription')
{{ Form::close() }}

@endsection
