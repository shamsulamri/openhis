@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Reset Billing Information
</h1>

<br>
<h3>
@if (!$lock)
Are you sure you want to reset the billing information ? 
@else
<p>
You have lock orders for this encounter. It is recommended that you reset the bill to get the latest billing items. 
</p>
<p>
Do you want to reset the billing information ?
</p>
@endif
<br>
<br>Warning all previous information will be lost.
<br>
<br>
<br>
	<a class="btn btn-primary" href="/bill_items/generate/{{ $encounter->encounter_id }}" role="button">Reset</a>
	<a class="btn btn-default" href="/bill_items/{{ $encounter->encounter_id }}" role="button">&nbsp;&nbsp;No&nbsp;&nbsp;</a>

</h3>
@endsection
