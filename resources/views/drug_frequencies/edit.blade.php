@extends('layouts.app')

@section('content')
<h1>
Edit Drug Frequency
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_frequency, ['route'=>['drug_frequencies.update',$drug_frequency->frequency_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('frequency_code', $drug_frequency->frequency_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drug_frequencies.drug_frequency')
{{ Form::close() }}

@endsection
