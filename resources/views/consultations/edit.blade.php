@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
canvas {
	border:0px solid #e5e5e5;
}

.small-font {
	font-size: 12px;
}

.dropdown-submenu {
    position: relative;
}

.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.table > tbody > tr:first-child > td {
    border: none;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
	}

.dropdown-submenu>a:after {
		display: block;
		content: " ";
		float: right;
		width: 0;
		height: 0;
		border-color: transparent;
		border-style: solid;
		border-width: 5px 0 5px 5px;
		border-left-color: #ccc;
		margin-top: 5px;
		margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}

label {
  display: block;
  padding-top: 5px;
  text-indent: -15px;
  font-size: 12px;
  font-weight: normal;
}
input {
  width: 13px;
  height: 13px;
  padding: 0;
  margin:0;
  vertical-align: bottom;
  position: relative;
  top: -1px;
  *overflow: hidden;
}

</style>
@include('consultations.panel')
<br>
<div class="tabs-container">
		<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#tab-1"><span class="glyphicon glyphicon-comment"></span></a></li>
				<li class=""><a data-toggle="tab" href="#tab-2"><span class="glyphicon glyphicon-pencil"></span></a></li>
		</ul>
		<div class="tab-content">
			<div id="tab-1" class="tab-pane active">
				<div class="panel-body">
						{{ Form::model($consultation, ['tabindex'=>1,'id'=>'my_form','route'=>['consultations.update',$consultation->consultation_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
						@include('consultations.consultation')
						{{ Form::close() }}
				</div>
			</div>
			<div id="tab-2" class="tab-pane">
				<div class="panel-body">
						<!--- Annotation -->
						@include('consultations.annotation')
						<!--- End -->
				</div>
			</div>
		</div>
</div>

<br>
<button class='btn btn-primary'>Save</button>
<div id='saveHTML' class='text text-success pull-right'></div>
<!--
<br>
<div class="tabs-container">
		<ul class="nav nav-tabs">
		</ul>
		<div class="tab-content">
			<div id="tab-1" class="tab-pane active">
				<div class="panel-body">
<div id='diagnosisHTML'></div>
{{ Form::text('diagnosis_clinical', null, ['tabindex'=>2,'id'=>'diagnosis_clinical','class'=>'form-control','placeholder'=>'Enter diagnosis','rows'=>'3']) }}
				</div>
			</div>
		</div>
</div>
-->
<!--
<h2>Procedures</h2>
{{ Form::text('procedure_description', null, ['tabindex'=>3,'id'=>'procedure_description','class'=>'form-control','placeholder'=>'','rows'=>'3']) }}
<br>
<div id='procedureHTML'>
</div>
-->

<!-- Medications -->
<!--
<br>
<div class="tabs-container">
		<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#tab-3"><span class="fa fa-eye"></span></a></li>
				<li class=""><a data-toggle="tab" href="#tab-4"><span class="fa fa-medkit"></span></a></li>
		</ul>
		<div class="tab-content">
			<div id="tab-3" class="tab-pane active">
				<div class="panel-body">
						@include('consultations.investigation')
				</div>
			</div>
			<div id="tab-4" class="tab-pane">
				<div class="panel-body">
						<div id="medicationList"></div>
						<input type='text' class='form-control' placeholder="Enter drug name" id='search' name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off'>
						<div id="drugList"></div>
				</div>
			</div>
		</div>
</div>
-->
<!-- End -->

<meta name="csrf-token" content="{{ csrf_token() }}">
	<script>
		keypressCount = 0;
		$(document).ready(function(){
			$('#consultation_notes').focusout(function(){
					saveNote();
				    //setTimeout(function () { $('#diagnosis_clinical').focus(); }, 20);
			});

			$('#consultation_notes').keypress(function(e){
					//console.log(e);
					$('#saveHTML').html("");
					keypressCount += 1;
					console.log(keypressCount);
					if (keypressCount>30) {
							saveNote();
							keypressCount=0;
					}


					if (e.key=='.') {
							saveNote();
							keypressCount=0;
					}

					if (e.charCode==13) {
							saveNote();
							keypressCount=0;
					}
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

		function saveNote() {
				console.log("Save note...");
					var note = $('#consultation_notes').val();
					note = encodeURIComponent(note);
					var dataString = "consultation_note="+note+"&id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "PUT",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "/consultations/{{ $consultation->consultation_id }}",
						data: dataString,
						success: function(data){
							$('#saveHTML').html("Saved...");
						}

					});
		}

		function removeDiagnosis(diagnosis_id) {
				var dataString = "diagnosis_id="+diagnosis_id;

				$.ajax({
				type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('diagnosis.drop') }}",
						data: dataString,
						success: function(data){
								$('#diagnosisHTML').html(data);
						}
				});
		}

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
		var erasing = false;
		var mousePos = { x:0, y:0 };
		var lastPos = mousePos;

		canvas.width = window.innerWidth-105;

		canvas.onblur = function(e) {
			saveAnnotation();
		}

		canvas.onmousedown = function(e) {
				disableBodyScroll(targetElement);
				drawing = true;
				lastPos = getMousePos(canvas, e);
				$('#saveHTML').html("");
		}

		canvas.onmouseup = function(e) {
				drawing = false;
				drawPos = coordinates.length;
				coor = new Coordinate(-1, -1);
				coordinates.push(coor);
				saveAnnotation();
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

		function saveAnnotation() {
				console.log("Save!!!!!!!!");
				var dataUrl = encodeURIComponent(canvas.toDataURL());
				dataString = "annotation_dataurl="+dataUrl;
				dataString = dataString + "&consultation_id={{ $consultation->consultation_id }}";
				dataString = dataString + "&annotation_image=99";
				dataString = dataString + "&annotation_image="+document.getElementById('selected_image').value;

				$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "/consultation_annotations",
						data: dataString,
						success: function(data){
							$('#saveHTML').html("Saved...");
							console.log("Save");
						}
				});

				enableBodyScroll(targetElement);
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
					if (erasing) {
						context.strokeStyle = '#FFFFFF';
						context.lineWidth=5.8;
					} else {
						context.strokeStyle = '#0000FF';
						context.lineWidth=0.3;
					}
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
						context.drawImage(
								this, 
								canvas.width/2-this.width/2,
							 	canvas.height/2-this.height/2);
				};

				imageObj.src = dataURL;

		}
			  
		/**
		var request = new XMLHttpRequest();
		request.open('GET', '/consultation_annotations/get/{{ $consultation->consultation_id }}/hopi.png', true);
		request.onreadystatechange = function() {
				if(request.readyState == 4) {
						if(request.status == 200) {
								loadCanvas(request.responseText);
						}
				}
		};

		request.send(null);
		**/

		function loadAnnotation(filename) {
				//var e = document.getElementById('images');
				//filename = e.options[e.selectedIndex].value;
				//if (filename == document.getElementById('selected_image').value) return;
				
				loadImage(filename);
				drawing = false;
				context.clearRect(0,0, canvas.width, canvas.height);
				context.beginPath();

				document.getElementById('selected_image').value = filename;

				if (filename != 'hopi.png') {
						document.getElementById('last_image').value = filename;
				}

				var request = new XMLHttpRequest();
				request.open('GET', '/consultation_annotations/get/{{ $consultation->consultation_id }}/'+filename, true);
				request.onreadystatechange = function() {
						if(request.readyState == 4) {
								if(request.status == 200) {
										loadCanvas(request.responseText);
								}
						}
				};
				request.send(null);
		}

		function loadImage(filename) {
				drawing = false;
				context.clearRect(0,0, canvas.width, canvas.height);
				var imageObj = new Image();
				imageObj.onload = function() {
						context.drawImage(this, 
								canvas.width/2-this.width*2/2,
							 	canvas.height/2-this.height*2/2,
								imageObj.width*2, imageObj.height*2);
				};

				imageObj.src = "/clinical_images/"+filename;
				context.beginPath();
				//document.getElementById('selected_image').value = filename;
				erasing = false;
				$('#btnErase').html("<span class='glyphicon glyphicon-pencil'></span>");
		}

		function clearAnnotation(annotationId) {
				filename = document.getElementById('selected_image').value;
				loadImage(filename);
				saveAnnotation();

				var request = new XMLHttpRequest();
				request.open('GET', '/consultation_annotations/clear/{{ $consultation->consultation_id }}/'+filename, true);
				request.onreadystatechange = function() {
						if(request.readyState == 4) {
										console.log("Image cleared.");
						}
				};
				request.send(null);

		}

		function lastAnnotation() {
				filename = document.getElementById('last_image').value;
				loadAnnotation(filename)
		}
		/**
		$('#images').on('click', function(){
				if (this.selectedIndex==-1) {
						imageSelected();
				} else {
						imageSelected();
				}
		});
		**/
		loadAnnotation('hopi.png');

</script>
<!--- Medication -->
<script>
$(document).ready(function(){

			$('#btnErase').click(function(){
					if (erasing) {
						$('#btnErase').html("<span class='glyphicon glyphicon-pencil'></span>");
					} else {
						$('#btnErase').html("<span class='fa fa-eraser'></span>");
					}
					erasing = !erasing;
					context.beginPath();

			});

			$(document).on('focusout', 'input', function(e) {
					var id = e.currentTarget.name;
					if (e.currentTarget.name != 'search') {
						updateDrug(id);
					}
			});

			$(document).on('focusout', 'select', function(e) {
					var id = e.currentTarget.name;
					if (e.currentTarget.name != 'search') {
						updateDrug(id);
					}
			});


			function updateDrug(id) {
					var dosage = $('#dosage_'.concat(id)).val();
					var frequency = $('#frequency_'.concat(id)).find('option:selected').val();
					var duration = $('#duration_'.concat(id)).val();
					var period = $('#period_'.concat(id)).find('option:selected').val();
					console.log(dosage,frequency, duration, period);

					var dataString = parse('drug_dosage=%s&frequency_code=%s&drug_duration=%s&period_code=%s&order_id=%s', dosage, frequency, duration, period, id);

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.update') }}",
						data: dataString,
						success: function(){
							console.log('drug updated...');
						}
					});
			}

			$('#search').keyup(function(e){
				if (e.keyCode == 13) {
						var value = $('#search').val();
						//if (e.which==13) {
						if (value.length >= 3) {
								var dataString = "search="+value+"&consultation_id={{ $consultation->consultation_id }}";

								$.ajax({
								type: "POST",
										headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
										url: "{{ route('medications.find') }}",
										data: dataString,
										success: function(data){
												$('#drugList').html(data);
										}
								});
								//$('#search').val('');
						}
				}
			});

			function addDrug2(id) {
					var dataString = "id="+id+"&consultation_id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.add') }}",
						data: dataString,
						success: function(data){
							$('#medicationList').html(data);
						}
					});

					$('#drugList').empty();
					$('#search').val('');
					$("#search").focus();
			}


			$(document).on('keydown', function ( e ) {
					// You may replace `c` with whatever key you want
					if (e.key == "Escape") {
							$("#search").focus();
					}
			});

			$('#drugList').empty();
			$('#search').val('');

			var note = $('#consultation_notes').val();
			$("#consultation_notes").focus().val("").val(note);

			$.ajax({
					type: "POST",
					headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
					url: "{{ route('medications.table') }}",
					success: function(data){
							$('#medicationList').html(data);
					}
			});

});

			function showHistory() {
					$.ajax({
							type: "POST",
							headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
							url: "{{ route('medications.history') }}",
							success: function(data){
									$('#drugList').html(data);
							}
					});
			}

			function parse(str) {
					var args = [].slice.call(arguments, 1),
							i = 0;

					return str.replace(/%s/g, () => args[i++]);
			}

			function addDrug(drug_code) {
					var dataString = "drug_code="+drug_code+"&consultation_id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.add') }}",
						data: dataString,
						success: function(data){
							$('#medicationList').html(data);
						}
					});

					$('#drugList').empty();
					$('#search').val('');
					$("#search").focus();
			}

			function removeDrug(order_id) {
					var dataString = "order_id="+order_id+"&consultation_id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.remove') }}",
						data: dataString,
						success: function(data){
							$('#medicationList').html(data);
						}
					});
			}

			function renewDrug(order_id) {
					var dataString = "order_id="+order_id+"&consultation_id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.renew') }}",
						data: dataString,
						success: function(data){
							$('#medicationList').html(data);
						}
					});
			}

			function addOrder(product_code) {
					var dataString = "product_code="+product_code+"&consultation_id={{ $consultation->consultation_id }}";

					console.log(document.getElementById(product_code).checked);

					if (document.getElementById(product_code).checked) {
							$.ajax({
								type: "POST",
								headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
								url: "{{ route('orders.add') }}",
								data: dataString,
								success: function(data){
									$('#orderList').html(data);
								}
							});

					} else {
							$.ajax({
								type: "POST",
								headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
								url: "{{ route('orders.remove') }}",
								data: dataString,
								success: function(data){
									$('#orderList').html(data);
								}
							});
					}

			}

			const targetElement = document.querySelector("#myCanvas");
			const disableBodyScroll = bodyScrollLock.disableBodyScroll;
			const enableBodyScroll = bodyScrollLock.enableBodyScroll;

			$('#consultation_notes').focus();
</script>
@endsection
