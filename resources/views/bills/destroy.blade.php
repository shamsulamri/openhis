@extends('layouts.app')

@section('content')
<h1>
Delete Bill
</h1>

<br>
<h3>
Are you sure you want to delete the selected record ?
<br>
<br>
Patient: {{ $bill->encounter->patient->patient_name }}<br>
MRN: {{ $bill->encounter->patient->patient_mrn }}<br>
Encounter Id: {{ $bill->encounter_id }}<br>
Encounter Id: {{ number_format($bill->bill_grand_total,2) }}<br>
<br>
{{ Form::open(['url'=>'bills/'.$bill->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bills" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
