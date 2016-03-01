@extends('layouts.app')

@section('content')
<h1>
Delete Diet Wastage
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $diet_wastage->waste_date }}
{{ Form::open(['url'=>'diet_wastages/'.$diet_wastage->waste_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/diet_wastages" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
