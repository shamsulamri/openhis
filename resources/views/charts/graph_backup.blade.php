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

/**
mark(ctx, 0,120, "x");
mark(ctx, 9,120, "o");
bar(ctx, 5,130);
text(ctx, "nil",10,180);
**/


for (x=0;x<x_tick_count+1;x++) {
		ctx.beginPath();
		ctx.strokeStyle="#BEBEBE";
		ctx.moveTo(x*x_tick_scale+margin_left,0+margin_top,0);
		ctx.lineTo(x*x_tick_scale+margin_left,canvas_height-margin_top, 30);
		ctx.stroke();
		
		@if (!$graph->x_tick_hide)
				ctx.fillText(x, x*x_tick_scale+margin_left-5,canvas_height-margin_top+15);
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

/*
function text(ctx,value, x,y) {
		ctx.beginPath();
		x = x*x_tick_scale;
		x = x + margin_left;
		y = y-y_tick_min;
		y = y/y_tick_multiplier;
		y = y_tick_count-y;
		y = y*y_tick_scale;
		y = y + margin_top;

		ctx.textAlign="center";
		ctx.fillText(value, x+(x_tick_scale/2), y-(y_tick_scale/2.5));

}

function label(ctx,value, x,y) {
		ctx.beginPath();
		x = x*x_tick_scale;
		x = x + margin_left;
		y = y-y_tick_min;
		y = y/y_tick_multiplier;
		y = y_tick_count-y;
		y = y*y_tick_scale;
		y = y + margin_top;

		ctx.textAlign="left";
		ctx.fillStyle="#999999";
		ctx.fillText(value, x+(x_tick_scale/2), y-(y_tick_scale/2.5));

}

function text_rotate(ctx,value, x,y) {
		ctx.beginPath();
		x = x*x_tick_scale;
		x = x + margin_left;
		y = y-y_tick_min;
		y = y/y_tick_multiplier;
		y = y_tick_count-y;
		y = y*y_tick_scale;
		y = y + margin_top;

		ctx.save();
		ctx.translate(0,0);
		ctx.rotate(Math.PI/2);
		ctx.fillText(value,{{ $graph->height/2 }}, -x-(x_tick_scale/2.5));
		ctx.textAlign="center";
		ctx.restore();

}

function line(ctx,x,y, x2, y2, width) {
		ctx.beginPath();
		x = x*x_tick_scale;
		x = x + margin_left;
		x2 = x2*x_tick_scale;
		x2 = x2 + margin_left;

		y = y-y_tick_min;
		y = y/y_tick_multiplier;
		y = y_tick_count-y;
		y = y*y_tick_scale;
		y = y + margin_top;

		y2 = y2-y_tick_min;
		y2 = y2/y_tick_multiplier;
		y2 = y_tick_count-y2;
		y2 = y2*y_tick_scale;
		y2 = y2 + margin_top;

		ctx.moveTo(x,y);
		ctx.lineTo(x2, y2);
		ctx.strokeStyle="#000000";
		ctx.lineWidth=width;
		ctx.stroke(); 
}

function curve(ctx,x,y,x1,y1, x2, y2, width) {
		ctx.beginPath();
		x = x*x_tick_scale;
		x = x + margin_left;
		x1 = x1*x_tick_scale;
		x1 = x1 + margin_left;
		x2 = x2*x_tick_scale;
		x2 = x2 + margin_left;

		y = y-y_tick_min;
		y = y/y_tick_multiplier;
		y = y_tick_count-y;
		y = y*y_tick_scale;
		y = y + margin_top;

		y1 = y1-y_tick_min;
		y1 = y1/y_tick_multiplier;
		y1 = y_tick_count-y1;
		y1 = y1*y_tick_scale;
		y1 = y1 + margin_top;

		y2 = y2-y_tick_min;
		y2 = y2/y_tick_multiplier;
		y2 = y_tick_count-y2;
		y2 = y2*y_tick_scale;
		y2 = y2 + margin_top;

		ctx.moveTo(x,y);
		ctx.quadraticCurveTo(x1, y1, x2, y2);
		ctx.strokeStyle="#000000";
		ctx.lineWidth=width;
		ctx.stroke(); 
}

function mark(ctx,x,y, type) {
		ctx.beginPath();
		x = x*x_tick_scale;
		x = x + margin_left;


		y = y-y_tick_min;
		y = y/y_tick_multiplier;

		y = y_tick_count-y;
		y = y*y_tick_scale;
		y = y + margin_top;

		if (type=="x") {
				ctx.moveTo(x,y);
				ctx.lineTo(x-5, y-5);
				ctx.lineTo(x+5, y+5);
				ctx.moveTo(x,y);
				ctx.lineTo(x+5, y-5);
				ctx.lineTo(x-5, y+5);
				ctx.strokeStyle="#000000";
				ctx.stroke(); 
		}

		if (type=="o") {
				radius=5;
				ctx.arc(x, y, radius, 0, 2 * Math.PI, false);
				ctx.strokeStyle = '#003300';
				ctx.stroke();
		}

		if (type=="O") {
				radius=3;
				ctx.arc(x, y, radius, 0, 2 * Math.PI, false);
				ctx.fillStyle = 'black';
				ctx.fill();
				ctx.strokeStyle = '#003300';
				ctx.stroke();
		}

		if (type=="<") {
				ctx.moveTo(x,y);
				ctx.lineTo(x-5, y-5);
				ctx.moveTo(x,y);
				ctx.lineTo(x+5, y-5);
				ctx.strokeStyle="#000000";
				ctx.stroke(); 
		}

		if (type==">") {
				ctx.moveTo(x,y);
				ctx.lineTo(x-5, y+5);
				ctx.moveTo(x,y);
				ctx.lineTo(x+5, y+5);
				ctx.strokeStyle="#000000";
				ctx.stroke(); 
		}
}

function bar(ctx,x,y, fillColor) {

		if (fillColor == '') fillColor = '#666666';
		ctx.beginPath();
		x = x*x_tick_scale;
		x = x + margin_left;

		y = y-y_tick_min;
		y = y/y_tick_multiplier;
		height = y;
		y = y_tick_count-y;
		y = y*y_tick_scale;
		y = y + margin_top;

		ctx.fillStyle=fillColor;
		ctx.fillRect(x,y,x_tick_scale, height*y_tick_scale);
		ctx.stroke(); 
}
 */
</script>
