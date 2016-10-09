@extends('layouts.app')

@section('content')
<h1>
Edit Stock Movement
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_movement, ['route'=>['stock_movements.update',$stock_movement->move_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('move_code', $stock_movement->move_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('stock_movements.stock_movement')
{{ Form::close() }}

@endsection
