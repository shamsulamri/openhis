@extends('layouts.app2')

@section('content')
<h3>
Delete Product
</h3>

<br>
<h4>
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $bill_material->product->product_name }}
<br>
{{ Form::open(['url'=>'bill_materials/'.$bill_material->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<br>
	<a class="btn btn-default" href="/bill_materials/index/{{ $bill_material->product_code }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
