@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Reload Billing Information
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to reload the billing information ? 
<br>
<br>Warning all previous information will be lost.
<br>
<br>
	<a class="btn btn-default" href="/bill_items/generate/{{ $encounter->encounter_id }}" role="button">Reload</a>
	<a class="btn btn-default" href="/bill_items/{{ $encounter->encounter_id }}" role="button">Cancel</a>

</h3>
@endsection
