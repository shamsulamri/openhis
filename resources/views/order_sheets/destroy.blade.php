@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Undo Cancellation
</h1>

<br>
<h3>
Are you sure you want to undo cancellation ?
{{ Form::open(['url'=>'order_sheet/'.$order_cancellation->cancel_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/order_sheet/{{ $order_cancellation->order->encounter_id }}" role="button">Cancel</a>
	{{ Form::submit('Undo', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
