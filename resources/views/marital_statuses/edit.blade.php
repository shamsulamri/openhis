@extends('layouts.app')

@section('content')
<h1>
Edit Marital Status
</h1>
@include('common.errors')
<br>
{{ Form::model($marital_status, ['route'=>['marital_statuses.update',$marital_status->marital_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>marital_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('marital_code', $marital_status->marital_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('marital_statuses.marital_status')
{{ Form::close() }}

@endsection