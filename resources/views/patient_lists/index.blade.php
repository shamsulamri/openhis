@extends('layouts.app')

@section('content')
<h1>Patient List</h1>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($location->encounter_code !='mortuary')
@include('patient_lists.outpatient')
@endif

@if ($location->encounter_code =='emergency')
<?php 
$patients = $observations;
$title = "Observation";
?>
@include('patient_lists.patients')
@endif

@if ($location->encounter_code !='mortuary')
<?php 
$patients = $inpatients;
$title = "Inpatient";
?>
@include('patient_lists.patients')
<?php 
$patients = $daycare;
$title = "Daycare";
?>
@include('patient_lists.patients')
@endif

@if ($location->encounter_code =='mortuary')
<?php 
$patients = $mortuary;
$title = "Mortuary";
?>
@include('patient_lists.patients')
@endif

@endsection
