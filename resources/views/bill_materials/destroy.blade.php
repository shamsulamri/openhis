@extends('layouts.app')

@section('content')
<h1>
Delete Bill Material
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $bill_material->product_code }}
{{ Form::open(['url'=>'bill_materials/'.$bill_material->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bill_materials" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
