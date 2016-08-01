@extends('layouts.app')

@section('content')
<h1>
Edit Order Set
</h1>
@include('common.errors')
<br>
{{ Form::model($order_set, ['route'=>['order_sets.update',$order_set->set_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>set_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('set_code', $order_set->set_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('order_sets.order_set')
{{ Form::close() }}

@endsection
