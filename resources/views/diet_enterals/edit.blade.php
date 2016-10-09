@extends('layouts.app')

@section('content')
<h1>
Edit Diet Enteral
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_enteral, ['route'=>['diet_enterals.update',$diet_enteral->enteral_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('enteral_code', $diet_enteral->enteral_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diet_enterals.diet_enteral')
{{ Form::close() }}

@endsection
