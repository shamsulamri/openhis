@extends('layouts.app')

@section('content')
<h1>
Delete Discharge Summary
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $discharge_summary->summary_treatment }}
{{ Form::open(['url'=>'discharge_summaries/'.$discharge_summary->encounter_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/discharge_summaries" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
