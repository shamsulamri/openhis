@extends('layouts.app')

@section('content')
<h1>
Edit Delivery Mode
</h1>
@include('common.errors')
<br>
{{ Form::model($delivery_mode, ['route'=>['delivery_modes.update',$delivery_mode->delivery_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('delivery_code', $delivery_mode->delivery_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('delivery_modes.delivery_mode')
{{ Form::close() }}

@endsection
