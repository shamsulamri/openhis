@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Delete Bill
</h1>

<br>
<h3>
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $bill->product->product_name }}
{{ Form::open(['url'=>'bill_items/'.$bill->bill_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bill_items/{{ $bill->encounter_id }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
