
<canvas id="myCanvas" width="{{ $graph->width }}" height="{{ $graph->height }}" style="border:1px solid #000000;" ></canvas>
<script>

canvas_width=800;
canvas_height=180;
margin_left=50;
margin_top=20;

x_tick_hide = true;

y_tick_multiplier=10; // Multiplier cannot be zero, default must be 1
y_tick_min = 100;

x_tick_count = 48;
y_tick_count = 8;
x_tick_scale = (canvas_width-(margin_left*2))/x_tick_count;
y_tick_scale = (canvas_height-(margin_top*2))/y_tick_count;

var c = document.getElementById("myCanvas");
var ctx = c.getContext("2d");

mark(ctx, 0,120, "x");
mark(ctx, 9,120, "o");
bar(ctx, 5,130);
text(ctx, "nil",10,180);

for (x=0;x<x_tick_count+1;x++) {
		ctx.beginPath();
		ctx.strokeStyle="#BEBEBE";
		ctx.moveTo(x*x_tick_scale+margin_left,0+margin_top,0);
		ctx.lineTo(x*x_tick_scale+margin_left,canvas_height-margin_top, 30);
		ctx.stroke();
		
		if (!x_tick_hide) {
				ctx.fillText(x, x*x_tick_scale+margin_left-5,canvas_height-5);
		}
}

for (y=0;y<y_tick_count+1;y++) {
		ctx.beginPath();
		ctx.strokeStyle="#BEBEBE";
		ctx.moveTo(0+margin_left, y*y_tick_scale+margin_top,0)
		ctx.lineTo(canvas_width-margin_left, y*y_tick_scale+margin_top,30);
		ctx.stroke();

		y_tick_value = y*y_tick_multiplier+y_tick_min;
		ctx.fillText(y_tick_value, margin_left-20 ,(canvas_height-(margin_top*2))-y*y_tick_scale+margin_top+5);
}



function text(ctx,value, x,y) {
		ctx.beginPath();
		x = x*x_tick_scale;
		x = x + margin_left;
		y = y-y_tick_min;
		y = y/y_tick_multiplier;
		y = y_tick_count-y;
		y = y*y_tick_scale;
		y = y + margin_top;

		ctx.fillText(value, x, y);

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
}

function bar(ctx,x,y) {
		ctx.beginPath();
		x = x*x_tick_scale;
		x = x + margin_left;

		y = y-y_tick_min;
		y = y/y_tick_multiplier;
		height = y;
		y = y_tick_count-y;
		y = y*y_tick_scale;
		y = y + margin_top;

		ctx.fillStyle="#666666";
		ctx.fillRect(x,y,x_tick_scale, height*y_tick_scale);
		ctx.stroke(); 
}
</script>
