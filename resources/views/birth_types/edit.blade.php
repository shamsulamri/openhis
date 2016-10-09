@extends('layouts.app')

@section('content')
<h1>
Edit Birth Type
</h1>
@include('common.errors')
<br>
{{ Form::model($birth_type, ['route'=>['birth_types.update',$birth_type->birth_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('birth_code', $birth_type->birth_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('birth_types.birth_type')
{{ Form::close() }}

@endsection
