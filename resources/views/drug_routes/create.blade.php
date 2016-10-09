@extends('layouts.app')

@section('content')
<h1>
New Drug Route
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_route, ['url'=>'drug_routes', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('route_code')) has-error @endif'>
        <label for='route_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('route_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('route_code')) <p class="help-block">{{ $errors->first('route_code') }}</p> @endif
        </div>
    </div>    
    
	@include('drug_routes.drug_route')
{{ Form::close() }}

@endsection
