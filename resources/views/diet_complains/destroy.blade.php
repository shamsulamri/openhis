@extends('layouts.app')

@section('content')
<h1>
Delete Diet Complain
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $diet_complain->complain_date }}
{{ Form::open(['url'=>'diet_complains/'.$diet_complain->complain_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/diet_complains" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
