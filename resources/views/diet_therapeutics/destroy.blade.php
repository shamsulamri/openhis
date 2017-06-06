@extends('layouts.app')

@section('content')
<h1>
Delete Diet Therapeutic
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $diet_therapeutic->therapeutic_code }}
{{ Form::open(['url'=>'diet_therapeutics/'.$diet_therapeutic->therapeutic_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/diet_therapeutics" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
