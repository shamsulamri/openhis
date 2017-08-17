@extends('layouts.app')

@section('content')
<h1>
Edit Drug Caution
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_caution, ['route'=>['drug_cautions.update',$drug_caution->caution_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('caution_code', $drug_caution->caution_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drug_cautions.drug_caution')
{{ Form::close() }}

@endsection
