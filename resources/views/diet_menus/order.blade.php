@extends('layouts.app')

@section('content')
<style>
th, td {
   text-align: center;
}
</style>
<h1>Diet Orders</h1>
<br>
<div class="row">
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Normal</strong></h5>	
				<h4><strong>{{ $dietHelper->diet('normal') }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Therapeutic</strong></h5>	
				<h4><strong>{{ $dietHelper->diet('therapeutic') }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Enteral</strong></h5>	
				<h4><strong>{{ $dietHelper->diet('enteral') }}</strong></h4>	
			</div>
		</div>
	</div>
</div>
<form class='form-inline' action='/diet_orders' method='post' name='myform'>
	<label>Diet&nbsp;</label>
	{{ Form::select('diet_code', $diets, $diet_code, ['class'=>'form-control','onchange'=>'reload()']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">	
</form>

<br>

<table class='table table-bordered'>
	<tr>
		<th width='20%'><div align='left'>Ward</div></th>
		@foreach ($diet_classes as $class)
		<th width='15%'>{{ $class->class_name }}</th>
		@endforeach
		<th width='15%'>Total</th>
	</tr>
	@foreach ($wards as $ward)
	<tr>
		<td><div align='left'>{{ $ward->ward_name }}</div></td>
		<?php $total=0; ?>
		@foreach ($diet_classes as $class)
		<?php $count=$dietHelper->order($diet_code,$class->class_code, $ward->ward_code); ?>
		<td width='15%'>{{ $count }}</td>
		<?php $total+=$count; ?>
		@endforeach
		<td>{{ $total }}</td>
	</tr>
	@endforeach
</table>
<script>
	function reload() {
			document.myform.submit();
	}
</script>
@endsection
