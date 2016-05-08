@extends('layouts.app')

@section('content')
@include('patients.id')
<h4>
<ul class="nav nav-tabs nav-justified">
  <li role="presentation"><a href="/encounters/{{ $encounter->encounter_id }}/edit">Step 1: Encounter</a></li>
  <li role="presentation" class="active"><a href="#">Step 2: Define Admission</a></li>
  <li role="presentation"><a href="#">Step 3: Bed Selection</a></li>
</ul>
</h4>
@include('common.errors')
<br>
{{ Form::model($admission, ['url'=>'admissions', 'class'=>'form-horizontal']) }} 
    
	@include('admissions.admission')
{{ Form::close() }}

@endsection
