@extends('layouts.app')

@section('content')
<h1>
Edit Registration
</h1>
@include('common.errors')
<br>
{{ Form::model($registration, ['route'=>['registrations.update',$registration->registration_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    <div class='form-group'>
        {{ Form::label('registration_code', 'registration_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			{{ $registration->registration_code }}
        </div>
    </div>
	@include('registrations.registration')
{{ Form::close() }}

@endsection
