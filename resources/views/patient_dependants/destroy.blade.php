@extends('layouts.app2')

@section('content')
<h1>
Delete Patient Dependant
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $patient_dependant->id }}
{{ Form::open(['url'=>'patient_dependants/'.$patient_dependant->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/dependants?patient_id={{ $patient_dependant->patient_id }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
