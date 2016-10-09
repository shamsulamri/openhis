@extends('layouts.app')

@section('content')
<h1>
Edit Ward
</h1>
@include('common.errors')
<br>
{{ Form::model($ward, ['route'=>['wards.update',$ward->ward_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			{{ Form::label('ward_code', $ward->ward_code, ['class'=>'control-label']) }}
		</div>
    </div>    
    
	@include('wards.ward')
{{ Form::close() }}

@endsection
