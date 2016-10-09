@extends('layouts.app')

@section('content')
<h1>
Edit Birth Complication
</h1>
@include('common.errors')
<br>
{{ Form::model($birth_complication, ['route'=>['birth_complications.update',$birth_complication->complication_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('complication_code', $birth_complication->complication_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('birth_complications.birth_complication')
{{ Form::close() }}

@endsection
