@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
canvas {border:1px solid #e5e5e5}
</style>
@include('consultations.panel')
<!--
<h2>Clinical Notes</h2>
{{ Form::model($consultation, ['tabindex'=>1,'id'=>'my_form','route'=>['consultations.update',$consultation->consultation_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
@include('consultations.consultation')
{{ Form::close() }}
-->

<h2>Consultation Note</h2>
<canvas tabindex=0 id="myCanvas" width="100%" height="500"></canvas>
<button class='btn btn-default' onclick="loadImage('eyes')">Eyes</button>
<button class='btn btn-default' onclick="loadImage('open_mouth_down')">Open Mouth</button>

<h2>Diagnoses</h2>
{{ Form::text('diagnosis_clinical', null, ['tabindex'=>2,'id'=>'diagnosis_clinical','class'=>'form-control','placeholder'=>'','rows'=>'3']) }}
<br>
<div id='diagnosisHTML'>
</div>

<!--
<h2>Procedures</h2>
{{ Form::text('procedure_description', null, ['tabindex'=>3,'id'=>'procedure_description','class'=>'form-control','placeholder'=>'','rows'=>'3']) }}
<br>
<div id='procedureHTML'>
</div>
-->

	<script>
		$(document).ready(function(){
			$('#consultation_notes').focusout(function(){
					var note = $('#consultation_notes').val();
					var dataString = "consultation_note="+note+"&id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "PUT",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "/consultations/{{ $consultation->consultation_id }}",
						data: dataString
					});

					    setTimeout(function () { $('#diagnosis_clinical').focus(); }, 20);
			});


			/** Diagnosis **/
			$('#diagnosis_clinical').keypress(function(e){
				if (e.which==13) {
					var value = $('#diagnosis_clinical').val();
					var dataString = "diagnosis_clinical="+value+"&id={{ $consultation->consultation_id }}";
					console.log(dataString);

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "/consultation_diagnoses",
						data: dataString,
						success: function(data){
							console.log(data);
							$('#diagnosisHTML').html(data);
						}
					});
					$('#diagnosis_clinical').val('');
				}
			});
			

			$.get('/consultation_diagnoses/encounter', function(data){
					$('#diagnosisHTML').html(data);
			})

			/** Procedures **/
			$('#procedure_description').keypress(function(e){
				if (e.which==13) {
					var value = $('#procedure_description').val();
					var dataString = "procedure_description="+value+"&id={{ $consultation->consultation_id }}";
					console.log(dataString);

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "/consultation_procedures",
						data: dataString,
						success: function(data){
							console.log(data);
							$('#procedureHTML').html(data);
						}
					});
					$('#procedure_description').val('');
				}
			});
			

			$.get('/consultation_procedures/encounter', function(data){
					$('#procedureHTML').html(data);
			})
		});
</script>
<script>
		/*** Annotation Section ***/

		class Coordinate {
				constructor(x,y) {
					this.x = x;
					this.y = y;
				}
		}

		coordinates = [];

		var drawPos =0;
		var drawRows = 0;
		var canvas = document.getElementById('myCanvas');
		var context = canvas.getContext('2d');
		var drawing = false;
		var mousePos = { x:0, y:0 };
		var lastPos = mousePos;

		canvas.width = window.innerWidth-105;

		canvas.onblur = function(e) {
			savenote();
		}

		canvas.onmousedown = function(e) {
				drawing = true;
				lastPos = getMousePos(canvas, e);
		}

		canvas.onmouseup = function(e) {
				drawing = false;
				drawPos = coordinates.length;
				coor = new Coordinate(-1, -1);
				coordinates.push(coor);
				savenote();
		}

		canvas.addEventListener('mousemove', function(evt) {
				if (drawing) {
						mousePos = getMousePos(canvas, evt);
						coor = new Coordinate(mousePos.x, mousePos.y);
						coordinates.push(coor);
						draw()
				}
		}, false);

		canvas.addEventListener("touchstart", function (e) {
				mousePos = getTouchPos(canvas, e);
				var touch = e.touches[0];
				var mouseEvent = new MouseEvent("mousedown", {
						clientX: touch.clientX,
						clientY: touch.clientY
				});
				canvas.dispatchEvent(mouseEvent);
		}, false);

		canvas.addEventListener('touchend', function(evt) {
				var mouseEvent = new MouseEvent("mouseup", {});
				canvas.dispatchEvent(mouseEvent);
				drawPos = coordinates.length;
				coor = new Coordinate(-1, -1);
				coordinates.push(coor);
		}, false);

		canvas.addEventListener("touchmove", function (e) {
				var touch = e.touches[0];
				var mouseEvent = new MouseEvent("mousemove", {
						clientX: touch.clientX,
						clientY: touch.clientY
				});
				canvas.dispatchEvent(mouseEvent);
		}, false);

		document.body.addEventListener("touchstart", function (e) {
				if (e.target == canvas) {
						e.preventDefault();
				}
		}, false);

		document.body.addEventListener("touchend", function (e) {
				if (e.target == canvas) {
						e.preventDefault();
				}
		}, false);

		document.body.addEventListener("touchmove", function (e) {
				if (e.target == canvas) {
						e.preventDefault();
				}
		}, false);

		function getMousePos(canvas, evt) {
				var rect = canvas.getBoundingClientRect();
				return {
						x: evt.clientX - rect.left,
						y: evt.clientY - rect.top
				};
		}

		function savenote() {
				var dataUrl = encodeURIComponent(canvas.toDataURL());
				dataString = "annotation_dataurl="+dataUrl;
				dataString = dataString + "&consultation_id={{ $consultation->consultation_id }}";
				dataString = dataString + "&annotation_image=99";

				$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "/consultation_annotations",
						data: dataString,
						success: function(data){
							console.log("Save");
						}
				});
		}

		/*
		$.get('/consultation_annotations/95', function(data){
				values = data.split("|");
				for (i=0;i<values.length-1;i++) {
					xy_values = values[i].split(":");
					x = xy_values[0];
					y = xy_values[1];
					console.log(x,y);
					coor = new Coordinate(x, y);
					coordinates.push(coor);
				}
				draw2();
				startDraw=false;
		})
		 */

		function draw() {
			if (drawing) {
					context.moveTo(lastPos.x, lastPos.y);
					context.lineTo(mousePos.x, mousePos.y);
					context.strokeStyle = '#0000FF';
					context.stroke();
					lastPos = mousePos;
			}
		}

		function draw2() {
			console.log(coordinates.length);
			startDraw = false
			for(i=drawPos; i<coordinates.length;i++) {
				if (coordinates[i].x == -1) {
						if (i<coordinates.length-1) {
								x1 = coordinates[i+1].x;
								y1 = coordinates[i+1].y;
								context.moveTo(x1,y1);
								startDraw = false;
								drawPos = i;
						}
				} else {
						if (i==drawPos) {
								x1 = coordinates[i].x;
								y1 = coordinates[i].y;
						}

						if (startDraw==false) {
								context.moveTo(x1,y1);
								startDraw=true;
						} else {
								x2 = coordinates[i].x;
								y2 = coordinates[i].y;
								context.lineTo(x2, y2);
								context.lineWidth=1;
								context.stroke(); 
								context.strokeStyle = '#0000FF';
								startDraw=false;
								
								x1 = coordinates[i].x;
								y1 = coordinates[i].y;
						}
				}
			}
		}

		function getTouchPos(canvasDom, touchEvent) {
				var rect = canvasDom.getBoundingClientRect();
				return {
						x: touchEvent.touches[0].clientX - rect.left,
						y: touchEvent.touches[0].clientY - rect.top
				};
		}


		function loadCanvas(dataURL) {
				var imageObj = new Image();
				imageObj.onload = function() {
						context.drawImage(this, 0, 0);
				};

				imageObj.src = dataURL;
		}
			  
		var request = new XMLHttpRequest();
		request.open('GET', '/consultation_annotations/get/{{ $consultation->consultation_id }}/99', true);
		request.onreadystatechange = function() {
				if(request.readyState == 4) {
						if(request.status == 200) {
								loadCanvas(request.responseText);
						}
				}
		};

		request.send(null);
		//loadImage('/clinical_images/eyes.png');

		function loadImage(filename) {
				drawing = false;
				context.clearRect(0,0, canvas.width, canvas.height);
				var imageObj = new Image();
				imageObj.onload = function() {
						context.drawImage(this, -100, 0, imageObj.width*2, imageObj.height*2);
				};

				imageObj.src = "/clinical_images/"+filename+".png";
				context.beginPath();
		}

</script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
