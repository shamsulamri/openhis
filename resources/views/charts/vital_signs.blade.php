
@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id')
@endif
<h1>
<a href='{{ URL::to('form',[$form->form_code, $encounter_id]) }}'>Forms</a> / Vital Signs
</h1>
<br>
<!-- Pulse & BP -->
<?php
	$graph = $Graph;
	$graph->height = 220;
	$graph->margin_top = 10;
	$graph->id = "pulsebp";
  	$graph->x_tick_hide=true;
	$graph->x_tick_count=48;
	$graph->x_value_scale = 30;
	$graph->x_axis_title = "";

	$graph->y_tick_multiplier=10;
	$graph->y_tick_min=60;	
	$graph->y_tick_count=12;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = false;
	$graph->y_axis_title = "Pulse and BP";
	$graph->y_axis_rotated = true;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = (DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			mark(ctx, {{ $x_value }}, {{ $value['pulse']?:0 }}, "o");
			mark(ctx, {{ $x_value }}, {{ $value['bp_systolic']?:0 }}, ">");
			mark(ctx, {{ $x_value }}, {{ $value['bp_diastolic']?:0 }}, "<");
			line(ctx, {{ $x_value }}, {{ $value['bp_systolic']?:0 }}, {{ $x_value }}, {{ $value['bp_diastolic']?:0 }},1);
@endforeach
</script>

<!-- Temp -->
<?php
	$graph = $Graph;
	$graph->height = 30;
	$graph->margin_top = 0;
	$graph->id = "vital_sign";
  	$graph->x_tick_hide=true;
	$graph->x_tick_count=24;
	$graph->x_value_scale = 60;
	$graph->x_axis_title = "";

	$graph->y_tick_multiplier=1;
	$graph->y_tick_min=0;	
	$graph->y_tick_count=1;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = 'true';
	$graph->y_axis_title = "Temp";
	$graph->y_axis_rotated = false;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			text(ctx,"{{ $value['temperature'] }}", {{ $x_value }}, 0 );
@endforeach
</script>
@endsection
