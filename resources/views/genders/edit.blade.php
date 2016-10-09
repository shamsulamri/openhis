@extends('layouts.app')

@section('content')
<h1>
Edit Gender
</h1>
@include('common.errors')
<br>
{{ Form::model($gender, ['route'=>['genders.update',$gender->gender_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('gender_code', $gender->gender_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('genders.gender')
{{ Form::close() }}

@endsection
