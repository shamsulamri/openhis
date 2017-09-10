@extends('layouts.app')

@section('content')
<h1>
Edit Bed Transaction
</h1>
@include('common.errors')
<br>
{{ Form::model($bed_transaction, ['route'=>['bed_transactions.update',$bed_transaction->transaction_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>transaction_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('transaction_code', $bed_transaction->transaction_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('bed_transactions.bed_transaction')
{{ Form::close() }}

@endsection
