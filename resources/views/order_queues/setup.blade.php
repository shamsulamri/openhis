@extends('layouts.app')

@section('content')

<h1>Order Queue Setup</h1>
	

<form action='/order_queues/setup' method='post'>
<h2>Encounters</h2>
@foreach ($encounters as $encounter)
		<?php $checked = 0; ?>
		@if (!empty($queue_encounters))
				@if (in_array($encounter->encounter_code, $queue_encounters)) 
					<?php $checked = 1 ?>
				@endif
		@endif
		{{ Form::checkbox($encounter->encounter_code, '1', $checked) }} {{ $encounter->encounter_name }}
		<br>
@endforeach

<h2>Product Categories</h2>
@foreach ($categories as $category)
		<?php $checked = 0; ?>
		@if (!empty($queue_categories))
				@if (in_array($category->category_code, $queue_categories)) 
					<?php $checked = 1 ?>
				@endif
		@endif
		{{ Form::checkbox($category->category_code, '1', $checked) }} {{ $category->category_name }}
		<br>
@endforeach

		<br>
		<button type="submit" class="btn btn-md btn-primary"> Save </button> 
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>

@endsection
