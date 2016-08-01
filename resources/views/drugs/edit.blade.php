@extends('layouts.app')

@section('content')
<h1>
Edit Drug
</h1>
@include('common.errors')
<br>
{{ Form::model($drug, ['route'=>['drugs.update',$drug->drug_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>drug_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('drug_code', $drug->drug_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drugs.drug')
{{ Form::close() }}

@endsection
