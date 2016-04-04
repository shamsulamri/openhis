@extends('layouts.app')

@section('content')
<h1>
Delete Admission Task
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $admission_task->product_code }}
{{ Form::open(['url'=>'admission_tasks/'.$admission_task->order_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/admission_tasks" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
