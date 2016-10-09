@extends('layouts.app')

@section('content')
<h1>
Edit Order Form
</h1>
@include('common.errors')
<br>
{{ Form::model($order_form, ['route'=>['order_forms.update',$order_form->form_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('form_code', $order_form->form_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('order_forms.order_form')
{{ Form::close() }}

@endsection
