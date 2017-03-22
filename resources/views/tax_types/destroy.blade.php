@extends('layouts.app')

@section('content')
<h1>
Delete Tax Type
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $tax_type->type_name }}
{{ Form::open(['url'=>'tax_types/'.$tax_type->type_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/tax_types" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
