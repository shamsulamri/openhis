
<!--- Annotation -->
<div class="form-inline">
		<div class="form-group">
			<button class='btn btn-success' id='btnErase'>Pen</button>
		</div>
		<div class="form-group">
				<button class='btn btn-default btn-sm' onclick="loadAnnotation('hopi.png')"><span class='fa fa-file-o'></span></button>
		</div>
		<!--
		<div class="form-group">
				<button class='btn btn-default btn-sm' onclick="loadAnnotation('hopi2.png')"><span class='fa fa-file-o'></span></button>
		</div>
		<div class="form-group">
				<button class='btn btn-default btn-sm' onclick="loadAnnotation('hopi3.png')"><span class='fa fa-file-o'></span></button>
		</div>
		<div class="form-group">
				<button class='btn btn-default btn-sm' onclick="loadAnnotation('hopi4.png')"><span class='fa fa-file-o'></span></button>
		</div>
		<div class="form-group">
				<button class='btn btn-default btn-sm' onclick="loadAnnotation('hopi5.png')"><span class='fa fa-file-o'></span></button>
		</div>
		-->
		@if ($consultation->encounter->patient->gender_code=='P')
				@include('consultations.female_images')
		@else
				@include('consultations.male_images')
		@endif
	<button class='btn btn-danger btn-sm pull-right' onclick="clearAnnotation()">X</button>
</div>
<br>
<canvas tabindex=0 id="myCanvas" width="100%" height="465"></canvas>
{{ Form::hidden('selected_image',null,['id'=>'selected_image']) }}
{{ Form::hidden('last_image',null,['id'=>'last_image']) }}

<!--- End: Annotation -->
