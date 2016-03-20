@extends('layouts.app')

@section('content')
<h1>
Delete Patient List
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $patient_list->encounter_id }}
{{ Form::open(['url'=>'patient_lists/'.$patient_list->queue_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/patient_lists" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
