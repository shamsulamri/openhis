@extends('layouts.app2')

@section('content')
<a class='btn btn-default' href='/purchase_lines/master_item/{{ $purchase->purchase_id }}'>Items</a>
<a class='btn btn-default' href='/purchases/master_document/{{ $purchase->purchase_id }}'>Documents</a>
<a class='btn btn-default' href='/product_searches?reason={{ $purchase->purchase_document }}&purchase_id={{ $purchase->purchase_id }}'>Products</a>
<br><br>
<form action='/purchase/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($purchases->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Document Number</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchases as $purchase)
	<tr>
			<td>
					<a href='{{ URL::to('purchase_lines/master_item/'. $purchase_id . '/'. $purchase->purchase_id) }}'>
						{{$purchase->purchase_number}}
					</a>
			</td>
			<td>
					{{ $purchase->purchase_date }}
			</td>
			<td align='right'>
					<a class='btn btn-primary btn-xs' href='{{ URL::to('purchase_lines/convert/'. $purchase->purchase_id.'/'.$purchase_id) }}'>
								<span class='glyphicon glyphicon-plus'></span>
					</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $purchases->appends(['search'=>$search])->render() }}
	@else
	{{ $purchases->render() }}
@endif
<br>
@if ($purchases->total()>0)
	{{ $purchases->total() }} records found.
@else
	No record found.
@endif

<script>
	var frameLine = parent.document.getElementById('frameLine');
	frameLine.src='/purchase_lines/detail/{{ $purchase_id }}';
</script>
@endsection
