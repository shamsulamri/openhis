@extends('layouts.app')

@section('content')
<h1>
Delete Medication Record
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $medication_record->order_id }}
{{ Form::open(['url'=>'medication_records/'.$medication_record->medication_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/medication_records" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
