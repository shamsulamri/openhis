@extends('layouts.app')

@section('content')
<h1>
Edit Tax Code
</h1>
@include('common.errors')
<br>
{{ Form::model($tax_code, ['route'=>['tax_codes.update',$tax_code->tax_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>tax_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('tax_code', $tax_code->tax_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('tax_codes.tax_code')
{{ Form::close() }}

@endsection