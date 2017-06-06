@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id')
@endif
<div class='row'>
@include('charts.line')
</div>
@endsection
