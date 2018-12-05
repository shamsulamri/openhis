@extends('layouts.app2')

@section('content')
<h3>
Delete Line Item
</h3>
<br>

<h4>
Are you sure you want to delete the selected record ?
<br>
<br>
<strong>{{ $purchase_line->product->product_name }}</strong>
{{ Form::open(['url'=>'purchase_lines/'.$purchase_line->line_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<br>
	<br>
	<br>
	<a class="btn btn-default" href="/purchase_lines/purchases/{{ $purchase_line->purchase_id }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
