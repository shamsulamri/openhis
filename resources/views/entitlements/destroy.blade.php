@extends('layouts.app')

@section('content')
<h1>
Delete Entitlement
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $entitlement->entitlement_code }}
{{ Form::open(['url'=>'entitlements/'.$entitlement->entitlement_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/entitlements" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
