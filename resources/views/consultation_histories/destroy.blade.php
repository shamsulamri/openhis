@extends('layouts.app')

@section('content')
<h1>
Delete Consultation History
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $consultation_history->history_id }}
{{ Form::open(['url'=>'consultation_histories/'.$consultation_history->history_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/consultation_histories" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
