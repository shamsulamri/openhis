@extends('layouts.app')

@section('content')
<h1>
New Registration
</h1>
@include('common.errors')
<br>
{{ Form::model($registration, ['url'=>'registrations', 'class'=>'form-horizontal']) }} 
    <div class='form-group'>
        {{ Form::label('registration_code', 'registration_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('registration_code', null, ['class'=>'form-control','placeholder'=>'']) }}
        </div>
    </div>


	@include('registrations.registration')
{{ Form::close() }}

@endsection
