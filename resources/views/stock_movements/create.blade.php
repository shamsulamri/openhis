@extends('layouts.app')

@section('content')
<h1>
New Stock Movement
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_movement, ['url'=>'stock_movements', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('move_code')) has-error @endif'>
        <label for='move_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('move_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('move_code')) <p class="help-block">{{ $errors->first('move_code') }}</p> @endif
        </div>
    </div>    
    
	@include('stock_movements.stock_movement')
{{ Form::close() }}

@endsection
