
@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id')
@endif
<h1>
<a href='{{ URL::to('form/partograph',[$encounter_id]) }}'>Forms</a> / Partograph
</h1>
<!--
<br>
<a href='/form/{{ $form->form_code }}/{{ $patient->patient_id }}/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
<a href='/partograph/{{ $encounter_id }}' class='btn btn-primary'><span class='fa fa-line-chart'></span> Partograph</a>
<a href='/form/{{ $form->form_code }}/{{ $encounter_id }}' class='btn btn-primary'><span class='fa fa-table'></span> Table</a>
<br>
-->
<br>
<!-- Fetal Heart Rate -->
<?php
	$graph = $Graph;
	$graph->id = "fetal_heart_rate";
	$graph->width = 800;
	$graph->height = 150;
	$graph->margin_top = 10;
	$graph->margin_left = 70;
  	$graph->x_tick_hide=true;
	$graph->x_tick_count=48;
	$graph->x_value_scale = 30;
	$graph->x_axis_title = "";

	$graph->y_tick_multiplier=10;
	$graph->y_tick_min=100;	
	$graph->y_tick_count=8;
	$graph->y_axis_title = "Fetal Heart Rate";
	$graph->y_tick_hide = false;
	$graph->y_axis_rotated = true;
?>
@include('charts.graph')

<script>
<?php
		$last_x = 0;
		$last_y = 0;
?>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = (DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			mark(ctx, {{ $x_value }}, {{ $value['fetal_heart_rate'] }}, "O");
			@if ($last_x != 0 or $last_y !=0)
			line(ctx, {{ $last_x }}, {{ $last_y }}, {{ $x_value }}, {{ $value['fetal_heart_rate'] }},1);
			@endif
<?php
			$last_x = $x_value;
			$last_y = $value['fetal_heart_rate']
?>
@endforeach
</script>

<!-- Liquor -->
<?php
	$graph = $Graph;
	$graph->height = 30;
	$graph->margin_top = 0;
	$graph->id = "liquor";
  	$graph->x_tick_hide='true';
	$graph->x_tick_count=24;
	$graph->x_value_scale = 60;
	$graph->x_axis_title = "";

	$graph->y_tick_multiplier=1;
	$graph->y_tick_min=0;	
	$graph->y_tick_count=1;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = 'true';
	$graph->y_axis_title = "Liquor";
	$graph->y_axis_rotated = false;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			text(ctx,"{{ $value['liquor'] }}", {{ $x_value }}, 0 );
@endforeach
</script>

<!-- Moulding -->
<?php
	$graph = $Graph;
	$graph->height = 30;
	$graph->margin_top = 0;
	$graph->id = "moulding";
  	$graph->x_tick_hide=true;
	$graph->x_tick_count=24;
	$graph->x_value_scale = 60;
	$graph->x_axis_title = "";

	$graph->y_tick_multiplier=1;
	$graph->y_tick_min=0;	
	$graph->y_tick_count=1;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = 'true';
	$graph->y_axis_title = "Moulding";
	$graph->y_axis_rotated = false;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			text(ctx,"{{ $value[$graph->id] }}", {{ $x_value }}, 0 );
@endforeach
</script>

<!--
<br>
@foreach ($graph_values as $graph_value)
	{{ $graph_values[0]->created_at }} {{ $graph_value->created_at }}<br>
	{{ DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at) }}<br>
	{{ (DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale) }}<br>
@endforeach
-->
<!-- Cervix -->
<?php
	$graph = $Graph;
	$graph->height = 300;
	$graph->margin_top = 20;
	$graph->id = "cervix";
  	$graph->x_tick_hide=false;
	$graph->x_tick_count=24;
	$graph->x_value_scale = 60;
	$graph->x_axis_title = "";

	$graph->y_tick_multiplier=1;
	$graph->y_tick_min=0;	
	$graph->y_tick_count=10;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = false;
	$graph->y_axis_title = "Cervix (x) Head Descent (o)";
	$graph->y_axis_rotated = true;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = (DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			mark(ctx, {{ $x_value }}, {{ $value['partograph_cervix']?:0 }}, "x");
			mark(ctx, {{ $x_value }}, {{ $value['partograph_descent']?:0 }}, "o");
@endforeach

line(ctx, 0,3,8,3,1);
line(ctx, 8,3,15,10,2);
line(ctx, 8,0,8,3,1);
line(ctx, 12,3,19,10,2);
label(ctx, "Latent Phase", 1,9);
label(ctx, "Active Phase", 10, 9);
label(ctx, "Alert", 9.7,6);
label(ctx, "Action", 13.5,6);
</script>


<!-- Contraction -->
<?php
	$graph = $Graph;
	$graph->height = 150;
	$graph->margin_top = 10;
	$graph->id = "contraction";
  	$graph->x_tick_hide=true;
	$graph->x_tick_count=24;
	$graph->x_value_scale = 60;
	$graph->x_axis_title = "";

	$graph->y_tick_multiplier=1;
	$graph->y_tick_min=0;	
	$graph->y_tick_count=5;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = false;
	$graph->y_axis_title = "Contraction per 10 mins";
	$graph->y_axis_rotated = true;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			bar(ctx, {{ $x_value }}, {{ $value['parto_contraction']?:0 }});
@endforeach
</script>

<!-- Oxytocin: UL -->
<?php
	$graph = $Graph;
	$graph->height = 30;
	$graph->margin_top = 0;
	$graph->id = "oxytocin_ul";
  	$graph->x_tick_hide='true';
	$graph->x_tick_count=24;
	$graph->x_value_scale = 60;
	$graph->x_axis_title = "";

	$graph->y_tick_multiplier=1;
	$graph->y_tick_min=0;	
	$graph->y_tick_count=1;
	$graph->y_axis_title = "";
	$graph->y_tick_hide = 'true';
	$graph->y_axis_title = "Oxytocin U/L";
	$graph->y_axis_rotated = false;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			text(ctx,"{{ $value['oxytocin'] }}", {{ $x_value }}, 0 );
@endforeach
</script>

<!-- Oxytocin: Drops -->
<?php
	$graph->id = "oxytocin_drops";
	$graph->y_axis_title = "Drops/min";
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			text(ctx,"{{ $value['oxytocin_drop_mins'] }}", {{ $x_value }}, 0 );
@endforeach
</script>

<!-- Oxytocin: Drops -->
<?php
	$graph->height = 150;
	$graph->id = "drugs_given";
	$graph->y_axis_title = "Drugs and IV Fluid";
	$graph->y_axis_rotated = true;
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			text_rotate(ctx,"{{ $value['drugs_given'] }}", {{ $x_value }}, 0 );
@endforeach
</script>
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

<!-- Urine: Temp -->
<?php
	$graph = $Graph;
	$graph->height = 30;
	$graph->margin_top = 0;
	$graph->id = "urine_vol";
  	$graph->x_tick_hide='true';
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

<!-- Urine: Albumin -->
<?php
	$graph->id = "urine_albumin";
	$graph->y_axis_title = "Albumin";
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			text(ctx,"{{ $value['albumin_list'] }}", {{ $x_value }}, 0 );
@endforeach
</script>

<!-- Urine: Sugar -->
<?php
	$graph->id = "urine_sugar";
	$graph->y_axis_title = "Sugar";
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			text(ctx,"{{ $value['sugar_list'] }}", {{ $x_value }}, 0 );
@endforeach
</script>

<!-- Urine: Acetone -->
<?php
	$graph->id = "urine_acetone";
	$graph->y_axis_title = "Acetone";
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			text(ctx,"{{ $value['acetone_list'] }}", {{ $x_value }}, 0 );
@endforeach
</script>
<!-- Urine: Volume -->
<?php
	$graph->id = "urine_volume";
	$graph->y_axis_title = "Volume";
?>
@include('charts.graph')

<script>
@foreach ($graph_values as $graph_value)
<?php
			$value = json_decode($graph_value->form_value,true);
			$x_value = round(DojoUtility::diffInMinutesBetweenDates($graph_values[0]->created_at, $graph_value->created_at)/$graph->x_value_scale);
 
?>
			text(ctx,"{{ $value['parto_urine_vol'] }}", {{ $x_value }}, 0 );
@endforeach
</script>
@endsection
