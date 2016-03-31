@extends('layouts.app')

@section('content')
@include('patients.label')
<h2>
Delete Medical Alert
</h2>
@include('common.errors')
<br>
<h4>
Are you sure you want to delete the selected record ?
{{ $medical_alert->patient_id }}
{{ Form::open(['url'=>'medical_alerts/'.$medical_alert->alert_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/medical_alerts" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
