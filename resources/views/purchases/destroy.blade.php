@extends('layouts.app')

@section('content')
<h1>
Delete Purchase
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $purchase->purchase_number }}
{{ Form::open(['url'=>'purchases/'.$purchase->purchase_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/purchases" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
