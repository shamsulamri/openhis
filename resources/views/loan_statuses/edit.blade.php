@extends('layouts.app')

@section('content')
<h1>
Edit Loan Status
</h1>
@include('common.errors')
<br>
{{ Form::model($loan_status, ['route'=>['loan_statuses.update',$loan_status->loan_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('loan_code', $loan_status->loan_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('loan_statuses.loan_status')
{{ Form::close() }}

@endsection
