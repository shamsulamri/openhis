@extends('layouts.app')

@section('content')
<h1>
Delete Loan Type
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $loan_type->type_name }}
{{ Form::open(['url'=>'loan_types/'.$loan_type->type_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/loan_types" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
