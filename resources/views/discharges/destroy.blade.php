@extends('layouts.app')

@section('content')
<h1>
Delete Discharge
</h1>

<br>
<h3>
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $discharge->encounter->patient->patient_name }}
({{ $discharge->encounter->patient->getMRN() }})
{{ Form::open(['url'=>'discharges/'.$discharge->discharge_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/discharges" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
