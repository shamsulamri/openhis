@extends('layouts.app')

@section('content')
<h1>
Edit Diet Complain
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_complain, ['route'=>['diet_complains.update',$diet_complain->complain_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>complain_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('complain_id', $diet_complain->complain_id, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diet_complains.diet_complain')
{{ Form::close() }}

@endsection
