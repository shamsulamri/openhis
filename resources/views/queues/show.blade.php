@extends('layouts.app')
@section('content')
@include('patients.id_only')
<h1>Queue Options</h1>
<br>
<h4>
				<span class='fa fa-clipboard' aria-hidden='true'></span>
				<a href='{{ URL::to('order_sheet/'. $encounter->encounter_id) }}'>Order Sheet</a>
				<br><br>

				<span class='fa fa-medkit' aria-hidden='true'></span>
				<a href="{{ URL::to('medication_record/mar/'. $encounter->encounter_id) }}">
					Medication Administration Record
				</a>
				<br><br>

				<span class='fa fa-users' aria-hidden='true'></span>
				<a href='{{ URL::to('queues/'. $queue->queue_id.'/edit') }}'>Edit Queue</a>
				<br><br>

</h4>

@endsection
