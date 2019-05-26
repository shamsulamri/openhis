
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
		curve(ctx, 0,3.2, 3, 9, 24, 12.2); // 0
		ctx.lineWidth=1;
		curve(ctx, 0,4.4, 2.6, 10.8, 24, 15.2); // +2SD
		curve(ctx, 0,5, 2.3, 11.8, 24, 17.1); // +3SD
		curve(ctx, 0,2.4, 4, 8, 24, 9.6); // -2SD
		curve(ctx, 0,2, 3.5, 7, 24, 8.7); // -3SD
		label(ctx, "0", 23.8,11.8);
		label(ctx, "+2SD", 23.8,15);
		label(ctx, "+3SD", 23.8,16.8);
		label(ctx, "-2SD", 23.8,9.2);
		label(ctx, "-3SD", 23.8,8.2);
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
		curve(ctx, 0,50, 3, 70, 24, 88); // 0
		ctx.lineWidth=1;
		curve(ctx, 0,46, 3, 68, 24, 82); // -2SD
		curve(ctx, 0,44, 3, 65, 24, 78); // -3SD
		curve(ctx, 0,54, 3, 75, 24, 94); // +2SD
		curve(ctx, 0,56, 3.8, 78, 24, 97); // +2SD
		label(ctx, "0", 23.8,86);
		label(ctx, "+2SD", 23.8,92);
		label(ctx, "+3SD", 23.8,95);
		label(ctx, "-2SD", 23.8,79.5);
		label(ctx, "-3SD", 23.8,75.5);
		line(ctx, 12,40,12,100,2);
</script>
@endsection
