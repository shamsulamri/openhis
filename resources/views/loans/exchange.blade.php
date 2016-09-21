@extends('layouts.app')

@section('content')
<h1>
Loan Exchange
</h1>
@include('common.errors')
<h3>
Are you sure you want to request exchange for the selected product ?
<br>
<br>
{{ $loan->item_code }}
<br>
<br>
{{ Form::open(['url'=>'loans/exchange/'.$loan->loan_id]) }}
	<a class="btn btn-default" href="/loans" role="button">Cancel</a>
	{{ Form::submit('Ok', ['class'=>'btn btn-default']) }}
{{ Form::close() }}

</h3>
@endsection
