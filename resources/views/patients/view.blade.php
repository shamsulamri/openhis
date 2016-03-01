@extends('layouts.app')

@section('content')
<h1>
{{ $patient->patient_name }}
</h1>
<br>
<h4>
<a href='{{ URL::to('encounters/create?patient_id='. $patient->patient_id ) }}'>Start Encounter</a>
<br>
<br>
<a href='{{ URL::to('patients/'. $patient->patient_id . '/edit?tab=demography') }}'>Edit Demography</a>
<br>
<br>
<a href='{{ URL::to('patients/'. $patient->patient_id . '/edit?tab=demography') }}'>Give Appointment</a>
<br>
<br>
<a href='{{ URL::to('patients/'. $patient->patient_id . '/edit?tab=demography') }}'>Define Dependants</a>
<br>
<br>
<a href='{{ URL::to('patients/'. $patient->patient_id . '/edit?tab=demography') }}'>Print Forms & Labels</a>


</h4>
@endsection
