@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Start Consultation
</h1>

<br>
<h3>
Are you sure you want to start a new consultation ?
<div class='pull-right'>
<a class="btn btn-default" href="/admissions" role="button">Cancel</a>

<a class='btn btn-primary' href='{{ URL::to('consultations/create?encounter_id='. $admission->encounter_id) }}'>Start</a>
</div>
</h3>
@endsection
