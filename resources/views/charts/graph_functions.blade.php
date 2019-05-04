<script>
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

function barPattern(ctx,x,y, pattern) {
		var img = document.getElementById("dots");
		var pat = ctx.createPattern(img, "repeat");

		x = x*x_tick_scale;
		x = x + margin_left;

		y = y-y_tick_min;
		y = y/y_tick_multiplier;
		height = y;
		y = y_tick_count-y;
		y = y*y_tick_scale;
		y = y + margin_top;

		ctx.rect(x,y,x_tick_scale, height*y_tick_scale);
		ctx.fillStyle = pat;
		ctx.fill();
}
</script>
