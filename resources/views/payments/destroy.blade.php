@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Delete Payment
</h1>
@include('common.errors')
<br>
<h4>
Are you sure you want to delete the selected record ?
<br>
<br>
Method: {{ $payment->method->payment_name }} <br>
Amount: {{ $payment->payment_amount }}
{{ Form::open(['url'=>'payments/'.$payment->payment_id]) }}
	{{ method_field('DELETE') }}
	<br>
	<br>
	<a class="btn btn-default" href="javascript:goBack()" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
