@extends('layouts.app')

@section('content')
<h1>
Delete Medical Alert
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $medical_alert->patient_id }}
{{ Form::open(['url'=>'medical_alerts/'.$medical_alert->alert_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/medical_alerts" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
