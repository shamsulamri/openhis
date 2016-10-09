@extends('layouts.app')

@section('content')
<h1>
Edit Nation
</h1>
@include('common.errors')
<br>
{{ Form::model($nation, ['route'=>['nations.update',$nation->nation_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('nation_code', $nation->nation_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('nations.nation')
{{ Form::close() }}

@endsection
