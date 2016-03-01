@extends('layouts.app')

@section('content')
<h1>
New Patient
</h1>
@include('common.errors')
<br>
<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="?tab=demography">Demography</a></li>
  <li role="presentation" class="disabled"><a href="?tab=contact">Contact</a></li>
  <li role="presentation" class="disabled"><a href="?tab=other">Other</a></li>
</ul>
<br>
{{ Form::model($patient, ['url'=>'patients', 'class'=>'form-horizontal']) }}
	@include('patients.patient')
{{ Form::close() }}

@endsection
