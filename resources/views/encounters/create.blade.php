@extends('layouts.app')

@section('content')
@include('patients.id')
<h4>
<ul class="nav nav-tabs nav-justified">
  <li role="presentation" class="active"><a href="#">Step 1: Encounter</a></li>
  <li role="presentation"><a href="#">Step 2: Admission</a></li>
  <li role="presentation"><a href="#">Step 3: Bed Selection</a></li>
</ul>
</h4>
<br>
@include('common.errors')

{{ Form::model($encounter, ['url'=>'encounters', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
{{ Form::close() }}

@endsection
