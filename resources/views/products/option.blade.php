@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Options</h1>
<br>
<h4>
<span class='glyphicon glyphicon-glass' aria-hidden='true'></span>
<a href='{{ URL::to('products/'. $product->product_code).'/edit' }}'>
		Edit Product 
</a>

<br>
<br>
<span class='glyphicon glyphicon-shopping-cart' aria-hidden='true'></span>
@if ($product->product_stocked==1)
<a href='{{ URL::to('stocks/'. $product->product_code) }}'>Stock Movements</a>
@else
Stock
@endif

<br>
<br>
<span class='glyphicon glyphicon-list' aria-hidden='true'></span>
@if ($product->product_bom==1)
<a href='{{ URL::to('bill_materials/'. $product->product_code) }}'>Bill of Materials</a>
@else
Bill of Materials
@endif


<br>
<br>
<span class='glyphicon glyphicon-cog' aria-hidden='true'></span>
@if ($product->category_code=='assembly')
<a href='{{ URL::to('build_assembly/'. $product->product_code) }}'>Build Assembly</a>
@else
Build Assemblies
@endif

<br>
<br>
<span class='glyphicon glyphicon-th' aria-hidden='true'></span>
@if ($product->category_code=='assembly')
<a href='{{ URL::to('explode_assembly/'. $product->product_code) }}'>Explode Assembly</a>
@else
Dismantle Assemblies
@endif

<br>
<br>
<span class='glyphicon glyphicon-pencil' aria-hidden='true'></span>
@if ($product->category_code=='drugs')
<a href='{{ URL::to('drug_prescriptions/'. $product->product_code.'/edit') }}'>Drug Prescription</a>
@else
Drug Prescription
@endif

<br>
<br>
<span class='glyphicon glyphicon-wrench' aria-hidden='true'></span>
<a href='{{ URL::to('product_maintenances/'. $product->product_code) }}'>Product Maintenance</a>

<br>
<br>
<span class='glyphicon glyphicon-leaf' aria-hidden='true'></span>
<a href='{{ URL::to('loans/request/'. $product->product_code) }}'>Loan</a>
</h4>
@endsection
