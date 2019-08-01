
@extends('layouts.app')

@section('content')
@include('charts.graph_functions')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id_only')
@endif
<h1>
<a href='/form/results/{{ $encounter_id }}'>Forms</a> /
{{ $form->form_name }}
</h1>
<!-- Pulse & BP -->
<?php
	$graph = $Graph;
	$graph->height = 350;
	$graph->width = 1000;
	$graph->margin_top = 35;
	$graph->id = "pulsebp";
  	$graph->x_tick_hide=true;
	$graph->x_tick_count=28;
	$graph->x_value_scale = 240;
	$graph->x_axis_title = "";

	$graph->y_tick_multiplier=10;
	$graph->y_tick_min=40;	
	$graph->y_tick_count=16;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = false;
	$graph->y_axis_title = "Pulse and BP";
	$graph->y_axis_rotated = true;
	$day_label_offset = 0;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = (DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
			if ($day_label_offset == 0) $day_label_offset = $graph_value->created_at;
?>
			mark(ctx, {{ $x_value }}, {{ $value['pulse']?:0 }}, "o");
			mark(ctx, {{ $x_value }}, {{ $value['bp_systolic']?:0 }}, ">");
			mark(ctx, {{ $x_value }}, {{ $value['bp_diastolic']?:0 }}, "<");
			line(ctx, {{ $x_value }}, {{ $value['bp_systolic']?:0 }}, {{ $x_value }}, {{ $value['bp_diastolic']?:0 }},1);
			text_rotate2(ctx,"{{ DojoUtility::timeReadFormat($graph_value->created_at) }}", 
					{{ $x_value-0.5 }}, 
					30
			);
@endforeach

<!-- Days -->
<?php
			$start_of_day = DojoUtility::startOfDay($encounter->created_at);
			$start_of_day2 = DojoUtility::dateReadFormat($start_of_day);
			$x_day_offset = DojoUtility::diffInMinutesBetweenDates($start_of_day, $day_label_offset)/$graph->x_value_scale;
			for ($i=1;$i<6;$i++) {
				$day_label = DojoUtility::addDays($start_of_day2, $i);
				$day_x = 0-$x_day_offset+((1440*$i)/$graph->x_value_scale);
?>
			@if ($day_x<29)
			line(ctx, {{ $day_x }},
						{{ $graph->y_tick_min }}, 
						{{ $day_x }},
						{{ ($graph->y_tick_count*$graph->y_tick_multiplier)+$graph->y_tick_min }},
						2,
						'#BEBEBE',
						[5,1]
			);

			text(ctx,'{{ DojoUtility::dateDayMonthFormat($day_label) }}', 
					{{ 0-$x_day_offset+((1440*$i)/$graph->x_value_scale)-0.5 }}, 
					{{ ($graph->y_tick_count*$graph->y_tick_multiplier)+$graph->y_tick_min }},
					'#666666'
			);
			@endif

<?php
			}
?>
<!-- End -->
</script>

<!-- Temp --> 
<?php
	$graph = $Graph;
	$graph->height = 400;
	$graph->margin_top = 50;
	$graph->id = "hourly";
  	$graph->x_tick_hide=false;
	$graph->x_tick_count=28;
	$graph->x_value_scale = 240;
	$graph->x_value_format = 'hour';
	$graph->x_axis_title = "4 Hourly";

	$graph->y_tick_multiplier=0.2;
	$graph->y_tick_min=35;
	$graph->y_tick_count=20;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = false;
	$graph->y_axis_title = "Temperature";
	$graph->y_axis_rotated = true;
?>
@include('charts.graph')

<script>
<?php 
	$x2=0; 
	$y2=0;
	$day_label_offset = 0;
?>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = (DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
			if ($day_label_offset == 0) $day_label_offset = $graph_value->created_at;
?>
			mark(ctx, {{ $x_value }}, {{ $value['temperature']?:0 }}, "O");
			text_rotate2(ctx,"{{ DojoUtility::timeReadFormat($graph_value->created_at) }}", {{ $x_value-0.5 }}, {{ $value['temperature']-0.3 }} );
		@if ($y2 > 0)
			line(ctx, {{ $x_value }}, {{ $value['temperature']?:0 }}, {{ $x2 }}, {{ $y2?:$graph->y_tick_min }},1);
		@endif

<?php
	$x2 = $x_value;
	$y2 = $value['temperature']?:$graph->y_tick_min;
?>
@endforeach
<!-- Days -->
<?php
			$start_of_day = DojoUtility::startOfDay($encounter->created_at);
			$start_of_day2 = DojoUtility::dateReadFormat($start_of_day);
			$x_day_offset = DojoUtility::diffInMinutesBetweenDates($start_of_day, $day_label_offset)/$graph->x_value_scale;
			for ($i=1;$i<6;$i++) {
				$day_label = DojoUtility::addDays($start_of_day2, $i);
				$day_x = 0-$x_day_offset+((1440*$i)/$graph->x_value_scale);
?>
			@if ($day_x<29)
			line(ctx, {{ $day_x }}, 
				{{ $graph->y_tick_min }}, 
				{{ $day_x }},
				{{ ($graph->y_tick_count*$graph->y_tick_multiplier)+$graph->y_tick_min }},
				2, 
				'#BEBEBE',
				[5,1]
			);

			text(ctx,'{{ DojoUtility::dateDayMonthFormat($day_label) }}', 
					{{ 0-$x_day_offset+((1440*$i)/$graph->x_value_scale)-0.5 }}, 
					{{ ($graph->y_tick_count*$graph->y_tick_multiplier)+$graph->y_tick_min }},
					'#666666'
			);
			@endif

<?php
			}
?>
<!-- End -->

</script>

<!-- Temp
<?php
	$graph = $Graph;
	$graph->height = 30;
	$graph->margin_top = 0;
	$graph->id = "time";
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
-->
@endsection
