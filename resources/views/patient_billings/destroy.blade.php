@extends('layouts.app')

@section('content')
<h1>
Delete Patient Billing
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $patient_billing->encounter_id }}
{{ Form::open(['url'=>'patient_billings/'.$patient_billing->bill_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/patient_billings" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
