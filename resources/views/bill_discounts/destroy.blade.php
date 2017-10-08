@extends('layouts.app')

@section('content')
<h1>
Delete Bill Discount
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $bill_discount->encounter_id }}
{{ Form::open(['url'=>'bill_discounts/'.$bill_discount->discount_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bill_discounts" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
