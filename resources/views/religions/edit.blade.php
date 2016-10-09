@extends('layouts.app')

@section('content')
<h1>
Edit Religion
</h1>
@include('common.errors')
<br>
{{ Form::model($religion, ['route'=>['religions.update',$religion->religion_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('religion_code', $religion->religion_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('religions.religion')
{{ Form::close() }}

@endsection
