@extends('layouts.app')

@section('content')
<h1>
Delete Tax Code
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $tax_code->tax_name }}
{{ Form::open(['url'=>'tax_codes/'.$tax_code->tax_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/tax_codes" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
