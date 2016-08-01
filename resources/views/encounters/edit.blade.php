@extends('layouts.app')

@section('content')
@include('patients.id')
@include('common.errors')

<h1>Edit Encounter</h1>
<br>
{{ Form::model($encounter, ['route'=>['encounters.update',$encounter->encounter_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/encounters" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>		
{{ Form::close() }}

@endsection
