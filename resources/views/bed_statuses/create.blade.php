@extends('layouts.app')

@section('content')
<h1>
New Bed Status
</h1>
@include('common.errors')
<br>
{{ Form::model($bed_status, ['url'=>'bed_statuses', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('status_code')) has-error @endif'>
        <label for='status_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('status_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'10.0']) }}
            @if ($errors->has('status_code')) <p class="help-block">{{ $errors->first('status_code') }}</p> @endif
        </div>
    </div>    
    
	@include('bed_statuses.bed_status')
{{ Form::close() }}

@endsection
