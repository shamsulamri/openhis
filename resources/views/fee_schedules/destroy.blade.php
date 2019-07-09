@extends('layouts.app')

@section('content')
<h1>
Delete Fee Schedule
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $fee_schedule->fee_code }}
{{ Form::open(['url'=>'fee_schedules/'.$fee_schedule->fee_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/fee_schedules" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
