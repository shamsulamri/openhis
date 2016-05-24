@extends('layouts.app')

@section('content')
<h1>
Delete Payment Method
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $payment_method->payment_name }}
{{ Form::open(['url'=>'payment_methods/'.$payment_method->payment_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/payment_methods" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
