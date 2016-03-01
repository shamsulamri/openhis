@extends('layouts.app')

@section('content')
<h1>
Delete Care Organisation
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $care_organisation->organisation_name }}
{{ Form::open(['url'=>'care_organisations/'.$care_organisation->organisation_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/care_organisations" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
