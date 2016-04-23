@extends('layouts.app2')

@section('content')
<h1>
Delete Dependant
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $dependant->patient_name }}
<br>
{{ Form::open(['url'=>'dependants/'.$dependant->patient_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<br>
	<br>
	<a class="btn btn-default" href="/dependants?patient_id={{$dependant->patient_id}}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
