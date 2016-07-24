@extends('layouts.app')

@section('content')
<h1>
Delete Loan Request
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $loan->item_code }}
{{ Form::open(['url'=>'loans/request/delete/'.$loan->loan_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/loans/ward" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
