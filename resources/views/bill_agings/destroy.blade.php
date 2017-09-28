@extends('layouts.app')

@section('content')
<h1>
Delete Bill Aging
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $bill_aging->age_amount }}
{{ Form::open(['url'=>'bill_agings/'.$bill_aging->encounter_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bill_agings" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
