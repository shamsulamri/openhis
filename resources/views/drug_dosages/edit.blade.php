@extends('layouts.app')

@section('content')
<h1>
Edit Drug Dosage
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_dosage, ['route'=>['drug_dosages.update',$drug_dosage->dosage_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('dosage_code', $drug_dosage->dosage_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drug_dosages.drug_dosage')
{{ Form::close() }}

@endsection
