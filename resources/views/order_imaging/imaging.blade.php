@extends('layouts.app')

@section('content')

<style>
select {
    height: 100px;
}
</style>

@if (!empty($consultation))
@can('module-consultation')
		@include('consultations.panel')		
		<h1>Plan</h1>
@endcan
@endif

<ul class="nav nav-tabs">
  <li @if ($plan=='laboratory') class="active" @endif><a href="/orders/plan?plan=laboratory">Laboratory</a></li>
  <li @if ($plan=='imaging') class="active" @endif><a href="/imaging">Imaging</a></li>
  <li><a href="/orders/procedure">Procedures</a></li>
  <li><a href="/orders/medication">Medications</a></li>
  <li @if ($plan=='fee_consultant') class="active" @endif><a href="/orders/plan?plan=fee_consultant">Fees</a></li>
  <li><a href="/orders/discussion">Discussion</a></li>
  <li><a href="/orders/make">Orders</a></li>
</ul>
<br>


<form id='form' action='/imaging' method='post' class='form-horizontal'>

<table style="width:100%">
  <tr>
	<td valign='top' width=500>

	<div class="row">
			<div class="col-xs-12">
			{{ Form::text('search', null, ['id'=>'search', 'class'=>'form-control', 'onkeypress'=>'find()', 'autocomplete'=>'off']) }}
			<br>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-12">
			{{ Form::select('product_code', $procedures,$product_code, ['id'=>'product_code','class'=>'form-control','size'=>'17', 'onchange'=>'productChanged()']) }}
			</div>
	</div>
	</td>
	<td width='10'>
	</td>
	<td valign='top'>
	<div class="row">
			<div class="col-xs-12">
					<div class='form-group  @if ($errors->has('side')) has-error @endif'>
						<div class='col-sm-12'>
							<p>Side</p>
							{{ Form::select('side', $sides,$side_code, ['id'=>'side','class'=>'form-control','size'=>'2']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-12">
					<div class='form-group  @if ($errors->has('region')) has-error @endif'>
						<div class='col-sm-12'>
							<p>Region</p>
							{{ Form::select('region', $regions,$region_code, ['id'=>'region', 'class'=>'form-control','size'=>'5']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-12">
					<div class='form-group  @if ($errors->has('view')) has-error @endif'>
						<div class='col-sm-12'>
							<p>View</p>
							{{ Form::select('view', $views, $view_code, ['onchange'=>'getValues()','id'=>'view', 'class'=>'form-control','size'=>'5', 'multiple'=>'multiple']) }}
						</div>
					</div>
			</div>
	</div>

	</td>
  </tr>
</table>
    {{ Form::submit('Add', ['id'=>'add', 'class'=>'btn btn-primary']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name="views" id="views" value="">
</form>

@if ($orders->count()>0)
<br>
<br>
<h3>Order List</h3>
<table class="table table-condensed">
@foreach ($orders as $order)
	<tr>
			
			<td>
				{{ $order->product->product_name }}
				<br>
				<small>
				{{ $order->product_code }}
				</small>
			</td>
			<td>
				{{ $order->order_description }}
			</td>
			<td>
				<a class='btn btn-danger btn-small pull-right' href='{{ URL::to('imaging/delete/'. $order->order_id) }}'>
					<span class='glyphicon glyphicon-minus'></span>
				</a>
			</td>
	</tr<
<br>
@endforeach
</table>
@endif
<script>
	var addButton = document.getElementById('add');

	procedures = [
			@foreach($params as $param)
			"{{ $param->product_code }}:{{ $param->product->product_name }}",
			@endforeach	
	];

	function productChanged() {
			params = [
					@foreach($params as $param)
					"{{ $param->product_code }}:{{ $param->side }}:{{ $param->region }}:{{ $param->view }}",
					@endforeach	
			];
		product_code = document.getElementById('product_code').value;

		var sideSelect = document.getElementById('side');
		var regionSelect = document.getElementById('region');
		var viewSelect = document.getElementById('view');

		clearList(sideSelect);
		clearList(regionSelect);
		clearList(viewSelect);

		for (var i=0;i<params.length;i++) {
				values = params[i].split(":")
				if (product_code==values[0]) {
						sides = values[1].split(";");
						addList(sideSelect,sides);
						regions = values[2].split(";");
						addList(regionSelect,regions);
						views = values[3].split(";");
						addList(viewSelect,views);
				}
		}
		//alert(sideSelect.options.length);
		if (sideSelect.options.length==0) addList(sideSelect,['']);
		if (regionSelect.options.length==0) addList(regionSelect,['']);
		if (viewSelect.options.length==0) addList(viewSelect,['']);
		
		addButton.disabled = false;
	}

	function clearList(selectedList) {
			var i;
			for(i=selectedList.options.length-1;i>=0;i--)
			{
					selectedList.remove(i);
			}
	}

	function addList(selectedList, list ) {
			for (var i=0;i<list.length;i++) {
					var optn = document.createElement("OPTION");
					optn.text = list[i];
					optn.value = list[i];
					if (list[i] != '') {
						selectedList.options.add(optn);
					}
			}
	}  

	function getValues() {
			var values = $('#view').val();
			$("#views").val(values);
	}

	function populateList() {
			procedures = [
					@foreach($params as $param)
					"{{ $param->product_code }}:{{ $param->product->product_name }}",
					@endforeach	
			];
	}

	function find() {

			var productList = document.getElementById('product_code');
			var sideSelect = document.getElementById('side');
			var regionSelect = document.getElementById('region');
			var viewSelect = document.getElementById('view');

			clearList(sideSelect);
			clearList(regionSelect);
			clearList(viewSelect);
			clearList(productList);
			addButton.disabled = true;

			searchWord = document.getElementById('search').value;

			console.log(searchWord);

			searchList = [];

			for (var i=0;i<procedures.length;i++) {
					values = procedures[i].split(":")
					//if (values[1].indexOf(searchWord) > -1) {
					if (values[1].toLowerCase().search(searchWord.toLowerCase()) > -1 || values[0].toLowerCase().search(searchWord.toLowerCase()) > -1 ) {
							searchList.push(values);
							var optn = document.createElement("OPTION");
							optn.text = unEntity(values[1]);
							optn.value = values[0];
							productList.options.add(optn);
					}
			}
			
	}

	productChanged();
	addButton.disabled = true;

	function unEntity(str){
			return str.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">");
	}

	$(document).ready(function() {
			$(window).keydown(function(event){
					if(event.keyCode == 13) {
							find();
							document.getElementById('search').select();
							event.preventDefault();
							return false;
					}
			});
	});
</script>
@endsection
