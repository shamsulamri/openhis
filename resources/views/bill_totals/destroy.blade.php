@extends('layouts.app')

@section('content')
<h1>
Delete Bill Total
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $bill_total->bill_total }}
{{ Form::open(['url'=>'bill_totals/'.$bill_total->encounter_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bill_totals" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
