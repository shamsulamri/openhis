@extends('layouts.app')

@section('content')
<h1>
Edit Loan Type
</h1>
@include('common.errors')
<br>
{{ Form::model($loan_type, ['route'=>['loan_types.update',$loan_type->type_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>type_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('type_code', $loan_type->type_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('loan_types.loan_type')
{{ Form::close() }}

@endsection
