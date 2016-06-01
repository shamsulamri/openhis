@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Delete Payment
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $payment->encounter_id }}
{{ Form::open(['url'=>'payments/'.$payment->payment_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bills/{{ $payment->encounter_id }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
