@extends('layouts.app')

@section('content')
<h1>
Edit Drug
</h1>
@include('common.errors')
<br>
{{ Form::model($drug, ['route'=>['drugs.update',$drug->drug_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('drug_code', $drug->drug_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drugs.drug')
{{ Form::close() }}

@endsection
