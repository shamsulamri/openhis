@extends('layouts.app')

@section('content')
<h1>
Edit Tax Type
</h1>
@include('common.errors')
<br>
{{ Form::model($tax_type, ['route'=>['tax_types.update',$tax_type->type_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('type_code', $tax_type->type_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('tax_types.tax_type')
{{ Form::close() }}

@endsection
