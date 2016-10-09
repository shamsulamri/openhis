@extends('layouts.app')

@section('content')
<h1>
Edit Drug Route
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_route, ['route'=>['drug_routes.update',$drug_route->route_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('route_code', $drug_route->route_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drug_routes.drug_route')
{{ Form::close() }}

@endsection
