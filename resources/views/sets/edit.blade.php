@extends('layouts.app')

@section('content')
<h1>
Edit Set
</h1>
@include('common.errors')
<br>
{{ Form::model($set, ['route'=>['sets.update',$set->set_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('set_code', $set->set_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('sets.set')
{{ Form::close() }}

@endsection
