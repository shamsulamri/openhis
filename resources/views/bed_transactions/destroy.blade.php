@extends('layouts.app')

@section('content')
<h1>
Delete Bed Transaction
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $bed_transaction->transaction_name }}
{{ Form::open(['url'=>'bed_transactions/'.$bed_transaction->transaction_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bed_transactions" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
