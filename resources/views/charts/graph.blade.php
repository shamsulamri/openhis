<canvas id="{{ $graph->id }}" width="{{ $graph->width }}" height="{{ $graph->height }}" style="border:0px solid #000000;" ></canvas>

<script>

canvas_width={{ $graph->width }};
canvas_height={{ $graph->height }};
margin_left= {{ $graph->margin_left }};
margin_top= {{ $graph->margin_top }};

y_tick_multiplier={{ $graph->y_tick_multiplier }}; // Multiplier cannot be zero, default must be 1
y_tick_min = {{ $graph->y_tick_min }};

x_tick_count = {{ $graph->x_tick_count }};
y_tick_count = {{ $graph->y_tick_count }};

x_tick_scale = (canvas_width-(margin_left*2))/x_tick_count;
y_tick_scale = (canvas_height-(margin_top*2))/y_tick_count;

var c = document.getElementById("{{ $graph->id }}");
var ctx = c.getContext("2d");
var x_tick_label = '';

for (x=0;x<x_tick_count+1;x++) {
		x_tick_label = x*{{ $graph->x_value_scale }}; // In minutes
		@if (!empty($graph->x_value_format))
				@if ($graph->x_value_format == 'hour')
						x_tick_label = x*{{ $graph->x_value_scale/60 }}; // In hours
				@endif
		@endif
		ctx.beginPath();
		ctx.strokeStyle="#BEBEBE";
		ctx.moveTo(x*x_tick_scale+margin_left,0+margin_top,0);
		ctx.lineTo(x*x_tick_scale+margin_left,canvas_height-margin_top, 30);
		ctx.stroke();
		
		@if (!$graph->x_tick_hide)
				ctx.fillText(x_tick_label, x*x_tick_scale+margin_left-5,canvas_height-margin_top+15);
		@endif
}
ctx.fillText("{{ $graph->x_axis_title }}", {{ ($graph->width-$graph->margin_left)/2 }}, canvas_height-margin_top+35);
ctx.textAlign="center";

for (y=0;y<y_tick_count+1;y++) {
		ctx.beginPath();
		ctx.strokeStyle="#BEBEBE";
		ctx.moveTo(0+margin_left, y*y_tick_scale+margin_top,0)
		ctx.lineTo(canvas_width-margin_left, y*y_tick_scale+margin_top,30);
		ctx.stroke();

		y_tick_value = y*y_tick_multiplier+y_tick_min;

		@if (!$graph->y_tick_hide)
				ctx.fillText(y_tick_value, margin_left-25 ,(canvas_height-(margin_top*2))-y*y_tick_scale+margin_top+5);
		@endif
}

@if ($graph->y_axis_rotated)
		ctx.save();
		ctx.translate(0,0);
		ctx.rotate(Math.PI/2);
		ctx.fillText("{{ $graph->y_axis_title }}",{{ $graph->height/2 }}, 0);
		ctx.textAlign="center";
		ctx.restore();
@else
		ctx.textAlign="left";
		ctx.fillText("{{ $graph->y_axis_title }}",0,{{ $graph->height/2 }});
@endif

</script>
