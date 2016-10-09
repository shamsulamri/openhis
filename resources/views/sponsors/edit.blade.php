@extends('layouts.app')

@section('content')
<h1>
Edit Sponsor
</h1>
@include('common.errors')
<br>
{{ Form::model($sponsor, ['route'=>['sponsors.update',$sponsor->sponsor_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('sponsor_code', $sponsor->sponsor_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('sponsors.sponsor')
{{ Form::close() }}

@endsection
