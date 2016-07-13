
@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #C0C0C0 solid; }
</style>
<h1>Diet Menu</h1>
<a href='/diet_menus' class='btn btn-default'>Back</a>
<br>
<br>
<div class="row">
	<div class="col-xs-6">
		<iframe name='frameIndex' id='frameIndex' width='100%' height='800px' src='/product_searches?reason=menu&class_code={{ $class_code }}&period_code={{ $period_code }}&week={{ $week }}&day={{ $day }}'></iframe>
	</div>
	<div class="col-xs-6">
		<iframe name='frameLine' id='frameLine' width='100%' height='800px' src='/diet_menus/menu/{{ $class_code }}/{{ $period_code }}/{{ $week }}/{{ $day }}'><iframe>
	</div>
</div>
@endsection
