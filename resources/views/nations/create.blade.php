@extends('layouts.app')

@section('content')
<h1>
New Nation
</h1>
@include('common.errors')
<br>
{{ Form::model($nation, ['url'=>'nations', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('nation_code')) has-error @endif'>
        <label for='nation_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('nation_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('nation_code')) <p class="help-block">{{ $errors->first('nation_code') }}</p> @endif
        </div>
    </div>    
    
	@include('nations.nation')
{{ Form::close() }}

@endsection
