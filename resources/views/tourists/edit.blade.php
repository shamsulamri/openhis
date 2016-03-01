@extends('layouts.app')

@section('content')
<h1>
Edit Tourist
</h1>
@include('common.errors')
<br>
{{ Form::model($tourist, ['route'=>['tourists.update',$tourist->tourist_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>tourist_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('tourist_code', $tourist->tourist_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('tourists.tourist')
{{ Form::close() }}

@endsection
