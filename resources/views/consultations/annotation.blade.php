
<!--- Annotation -->
<div class="form-inline">
		<div class="form-group">
			<button class='btn btn-success btn-sm' id='btnErase'>Pen</button>
		</div>
		<div class="form-group">
				<button class='btn btn-default btn-sm' onclick="loadAnnotation('hopi.png')">1</span></button>
		</div>
		<div class="form-group">
				<button class='btn btn-default btn-sm' onclick="loadAnnotation('hopi2.png')">2</button>
		</div>
		<div class="form-group">
				<button class='btn btn-default btn-sm' onclick="loadAnnotation('hopi3.png')">3</button>
		</div>
		<div class="form-group">
				<button class='btn btn-default btn-sm' onclick="loadAnnotation('hopi4.png')">4</button>
		</div>
		<div class="form-group">
				<button class='btn btn-default btn-sm' onclick="loadAnnotation('hopi5.png')">5</button>
		</div>
		@if ($consultation->encounter->patient->gender_code=='P')
				@include('consultations.female_images')
		@else
				@include('consultations.male_images')
		@endif
	<button class='btn btn-danger btn-sm pull-right' onclick="clearAnnotation()">Clear</button>
</div>
<br>
<canvas tabindex=0 id="myCanvas" width="100%" height="800"></canvas>
{{ Form::hidden('selected_image',null,['id'=>'selected_image']) }}
{{ Form::hidden('last_image',null,['id'=>'last_image']) }}

<!--- End: Annotation -->
