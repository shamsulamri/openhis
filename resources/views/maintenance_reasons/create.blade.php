@extends('layouts.app')

@section('content')
<h1>
New Maintenance Reason
</h1>
@include('common.errors')
<br>
{{ Form::model($maintenance_reason, ['url'=>'maintenance_reasons', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('reason_code')) has-error @endif'>
        <label for='reason_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('reason_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('reason_code')) <p class="help-block">{{ $errors->first('reason_code') }}</p> @endif
        </div>
    </div>    
    
	@include('maintenance_reasons.maintenance_reason')
{{ Form::close() }}

@endsection
