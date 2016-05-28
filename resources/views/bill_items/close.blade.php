@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Financial Discharge
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to close the patient billing details ?
<br>
<br>
	<a class="btn btn-default" href="#" role="button">Close</a>
	<a class="btn btn-default" href="/bills/{{ $encounter->encounter_id }}" role="button">Cancel</a>

</h3>
@endsection
