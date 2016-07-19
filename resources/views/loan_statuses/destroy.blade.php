@extends('layouts.app')

@section('content')
<h1>
Delete Loan Status
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $loan_status->load_code }}
{{ Form::open(['url'=>'loan_statuses/'.$loan_status->loan_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/loan_statuses" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
