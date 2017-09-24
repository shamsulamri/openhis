@extends('layouts.app')

@section('content')
<h1>
Delete Payment Credit
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $payment_credit->card_code }}
{{ Form::open(['url'=>'payment_credits/'.$payment_credit->credit_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/payment_credits" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
