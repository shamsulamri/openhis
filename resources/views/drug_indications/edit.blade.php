@extends('layouts.app')

@section('content')
<h1>
Edit Drug Indication
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_indication, ['route'=>['drug_indications.update',$drug_indication->indication_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('indication_code', $drug_indication->indication_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drug_indications.drug_indication')
{{ Form::close() }}

@endsection
