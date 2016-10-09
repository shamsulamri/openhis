@extends('layouts.app')

@section('content')
<h1>
Edit Frequency
</h1>
@include('common.errors')
<br>
{{ Form::model($frequency, ['route'=>['frequencies.update',$frequency->frequency_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('frequency_code', $frequency->frequency_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('frequencies.frequency')
{{ Form::close() }}

@endsection
