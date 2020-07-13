@extends('layouts.app')

@section('content')
@include('patients.id_only')

<h1>Order Cancellation</h1>
<br>
{{ Form::model($order_cancellation, ['url'=>'order_sheet/cancel/'.$order->encounter_id, 'class'=>'form-horizontal']) }} 
    <div class='form-group'>
        <label for='order' class='col-sm-3 control-label'>Order</span></label>
        <div class='col-sm-9'>
            {{ Form::label('order', $order->product->product_name, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('cancel_reason')) has-error @endif'>
        <label for='cancel_reason' class='col-sm-3 control-label'>Reason<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::textarea('cancel_reason', $order_cancellation->cancel_reason, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('cancel_reason')) <p class="help-block">{{ $errors->first('cancel_reason') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/order_sheet/{{ $order->encounter_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
			@if ($cancel_id)
            <a class="btn btn-warning pull-right" href="/order_sheet/delete/{{ $cancel_id }}" role="button">Undo Cancellation</a>
			@endif
        </div>
    </div>

    {{ Form::hidden('order_id', $order->order_id) }}
    {{ Form::hidden('cancel_id', $cancel_id) }}
{{ Form::close() }}
@endsection
