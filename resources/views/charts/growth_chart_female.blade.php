
@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id')
@endif
<h1>
<a href='{{ URL::to('form',[$form->form_code, $encounter_id]) }}'>Forms</a> / {{ $form->form_name }}
</h1>
@include('charts.graph_functions')
<!-- Weight -->
<?php
	$graph = $Graph;
	$graph->height = 600;
	$graph->margin_top = 40;
	$graph->id = "weight";
  	$graph->x_tick_hide=false;
	$graph->x_tick_count=24;
	$graph->x_value_scale = 1;
	$graph->x_axis_title = "Age (months)";

	$graph->y_tick_multiplier=1;
	$graph->y_tick_min=0;	
	$graph->y_tick_count=20;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = false;
	$graph->y_axis_title = "Weight (kg)";
	$graph->y_axis_rotated = true;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = (DojoUtility::diffInMonthsBetweenDates($patient->patient_birthdate, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			mark(ctx, {{ $x_value }}, {{ $value['weight']?:0 }}, "x");
@endforeach

		ctx.lineWidth=3;
		curve(ctx, 0,3.2, 3, 9, 24, 11.4); // 0
		ctx.lineWidth=1;
		curve(ctx, 0,4.2, 3, 10.4, 24, 14.8); // +2SD
		curve(ctx, 0,4.8, 1.3, 10.6, 24, 17.1); // +3SD
		curve(ctx, 0,2.4, 2, 6, 24, 9); // -2SD
		curve(ctx, 0,2, 2, 5.5, 24, 8); // -3SD
		label(ctx, "0", 23.8,11);
		label(ctx, "+2SD", 23.8,14.3);
		label(ctx, "+3SD", 23.8,16.5);
		label(ctx, "-2SD", 23.8,8.6);
		label(ctx, "-3SD", 23.8,7.4);
		line(ctx, 12,0,12,20,2);
</script>

<!-- Length -->
<?php
	$graph = $Graph;
	$graph->height = 450;
	$graph->margin_top = 40;
	$graph->id = "length";
  	$graph->x_tick_hide=false;
	$graph->x_tick_count=24;
	$graph->x_value_scale = 1;
	$graph->x_axis_title = "Age (months)";

	$graph->y_tick_multiplier=5;
	$graph->y_tick_min=40;	
	$graph->y_tick_count=12;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = false;
	$graph->y_axis_title = "Length (cm)";
	$graph->y_axis_rotated = true;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = (DojoUtility::diffInMonthsBetweenDates($patient->patient_birthdate, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			mark(ctx, {{ $x_value }}, {{ $value['length']?:0 }}, "x");
@endforeach

		ctx.lineWidth=3;
		curve(ctx, 0,49, 2.6, 67, 24, 87); // 0
		ctx.lineWidth=1;
		curve(ctx, 0,46, 3, 63, 24, 80); // -2SD
		curve(ctx, 0,44, 2, 60, 24, 77); // -3SD
		curve(ctx, 0,53, 3, 72, 24, 93); // +2SD
		curve(ctx, 0,55, 3.8, 76, 24, 96); // +2SD
		label(ctx, "0", 23.8,85);
		label(ctx, "+2SD", 23.8,90.4);
		label(ctx, "+3SD", 23.8,93.6);
		label(ctx, "-2SD", 23.8,78);
		label(ctx, "-3SD", 23.8,74);
		line(ctx, 12,40,12,100,2);
</script>
@endsection
