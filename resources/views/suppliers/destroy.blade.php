@extends('layouts.app')

@section('content')
<h1>
Delete Supplier
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $supplier->supplier_name }}
{{ Form::open(['url'=>'suppliers/'.$supplier->supplier_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/suppliers" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
