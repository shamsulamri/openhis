@extends('layouts.app2')

@section('content')
<h3>
Delete Dependant
</h3>
@include('common.errors')
<br>
<h4>
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $patient_dependant->patient->patient_name }}
<br>
<br>
{{ Form::open(['url'=>'patient_dependants/'.$patient_dependant->id]) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/dependants?patient_id={{ $patient_dependant->patient_id }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
