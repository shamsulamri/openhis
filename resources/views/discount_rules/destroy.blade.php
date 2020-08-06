@extends('layouts.app')

@section('content')
<h1>
Delete Discount Rule
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $discount_rule->sponsor_code }}
{{ Form::open(['url'=>'discount_rules/'.$discount_rule->rule_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/discount_rules" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
