@extends('layouts.app2')

@section('content')
@if ($reason=='bulk')
<a class="btn btn-default" href="{{ url('stock_inputs/input/'.$return_id) }}" role="button">Back</a>
@elseif ($reason=='purchase')
<a class="btn btn-default" href="{{ url('purchase_lines/detail/'.$return_id) }}" role="button">Back</a>
@elseif ($reason=='bom')
<a class="btn btn-default" href="{{ url('bill_materials/index/'.$return_id) }}" role="button">Back</a>
@else
<a class="btn btn-default" href="{{ url('order_sets/index/'.$return_id) }}" role="button">Back</a>
@endif
<br>
<br>
@include('products.product_enquiry')
@endsection
