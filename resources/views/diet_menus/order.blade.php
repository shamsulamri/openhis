@extends('layouts.app')

@section('content')
<h1>Diet Orders</h1>
<br>
<table class='table table-bordered'>
	<tr>
		<td align='middle'>Normal</td>
		<td align='middle'>Therapeutic</td>
		<td align='middle'>Enteral</td>
	</tr>
	<tr>
		<td align='middle'>{{ $dietHelper->diet('normal') }}</td>
		<td align='middle'>{{ $dietHelper->diet('therapeutic') }}</td>
		<td align='middle'>{{ $dietHelper->diet('enteral') }}</td>
	</tr>
</table>
<form class='form-inline' action='/diet_orders' method='post' name='myform'>
	<label>Diet&nbsp;</label>
	{{ Form::select('diet_code', $diets, $diet_code, ['class'=>'form-control','onchange'=>'reload()']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">	
</form>

<br>

<table class='table table-bordered'>
	<tr>
		<th width='20%'>Ward</th>
		@foreach ($diet_classes as $class)
		<th width='15%'>{{ $class->class_name }}</th>
		@endforeach
		<th width='15%'>Total</th>
	</tr>
	@foreach ($wards as $ward)
	<tr>
		<td>{{ $ward->ward_name }}</td>
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
