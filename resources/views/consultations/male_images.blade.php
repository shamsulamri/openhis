	
@if ($patient->patientAgeInYears()<=2 or substr($patient->patient_name,0,3) == 'B/O')
	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Baby&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('baby_back.png')">Baby Back</a></li>
						<li><a href="javascript:loadAnnotation('baby_body_front_m.png')">Baby Back Front </a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Child&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('child_abdomen_m.png')">Child Abdomen </a></li>
						<li><a href="javascript:loadAnnotation('child_back.png')">Child Back</a></li>
						<li><a href="javascript:loadAnnotation('child_body_front_m.png')">Child Body Front </a></li>
						<li><a href="javascript:loadAnnotation('child_chest_l_side.png')">Child Chest L Side</a></li>
						<li><a href="javascript:loadAnnotation('child_chest_r_side.png')">Child Chest R Side</a></li>
				</ul>
		</div>
	</div>
@else
	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Body&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('abdomen_m.png')">Abdomen</a></li>
						<li><a href="javascript:loadAnnotation('body_front_dermatome.png')">Body Front Dermatome</a></li>
						<li><a href="javascript:loadAnnotation('body_back_dermatome.png')">Body Back Dermatome</a></li>
						<li><a href="javascript:loadAnnotation('body_front_m.png')">Body Front </a></li>
						<li><a href="javascript:loadAnnotation('body_back_m.png')">Body Back </a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Chest
						&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('chest_back.png')">Chest Back</a></li>
						<li><a href="javascript:loadAnnotation('chest_front_m.png')">Chest Front </a></li>
						<li><a href="javascript:loadAnnotation('chest_heart.png')">Chest Heart</a></li>
						<li><a href="javascript:loadAnnotation('chest_l_side_m.png')">Chest L Side </a></li>
						<li><a href="javascript:loadAnnotation('chest_r_side_m.png')">Chest R Side </a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Ear&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('r_ear.png')">Right Ear</a></li>
						<li><a href="javascript:loadAnnotation('l_ear.png')">Left Ear</a></li>
						<li><a href="javascript:loadAnnotation('r_tympanic_membrane.png')">Right Tympanic Membrane</a></li>
						<li><a href="javascript:loadAnnotation('l_tympanic_membrane.png')">Left Tympanic Membrane</a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Eye&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('eyes.png')">Eyes</a></li>
						<li><a href="javascript:loadAnnotation('r_eye.png')">Right Eye</a></li>
						<li><a href="javascript:loadAnnotation('r_fundus.png')">Right Fundus</a></li>
						<li><a href="javascript:loadAnnotation('l_eye.png')">Left Eye</a></li>
						<li><a href="javascript:loadAnnotation('l_fundus.png')">Left Fundus</a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Face&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('face_m.png')">Face</a></li>
						<li><a href="javascript:loadAnnotation('face_r_m.png')">Right Face</a></li>
						<li><a href="javascript:loadAnnotation('face_l_m.png')">Left Face</a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<button class='btn btn-primary btn-sm' onclick="loadAnnotation('genitalia_m.png')">Genital</button>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Limb&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('r_arm.png')">Right Arm</a></li>
						<li><a href="javascript:loadAnnotation('r_hand.png')">Right Hand</a></li>
						<li><a href="javascript:loadAnnotation('r_leg.png')">Right Leg</a></li>
						<li><a href="javascript:loadAnnotation('l_arm.png')">Left Arm</a></li>
						<li><a href="javascript:loadAnnotation('l_hand.png')">Left Hand</a></li>
						<li><a href="javascript:loadAnnotation('l_leg.png')">Left Leg</a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Mouth&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('open_mouth_down.png')">Tongue Down</a></li>
						<li><a href="javascript:loadAnnotation('open_mouth_up.png')">Tongue Up</a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Neck&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('neck_front.png')">Neck Front</a></li>
						<li><a href="javascript:loadAnnotation('r_neck_m.png')">Right Neck </a></li>
						<li><a href="javascript:loadAnnotation('l_neck_m.png')">Left Neck </a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<button class='btn btn-primary btn-sm' onclick="loadAnnotation('rectal_m.png')">Rectal</button>
	</div>

<!--
	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Body&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('abdomen_m.png')">Abdomen</a></li>
						<li><a href="javascript:loadAnnotation('body_front_dermatome.png')">Body Front Dermatome</a></li>
						<li><a href="javascript:loadAnnotation('body_back_dermatome.png')">Body Back Dermatome</a></li>
						<li><a href="javascript:loadAnnotation('body_front_m.png')">Body Front </a></li>
						<li><a href="javascript:loadAnnotation('body_back_m.png')">Body Back </a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Chest
						&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('chest_back.png')">Chest Back</a></li>
						<li><a href="javascript:loadAnnotation('chest_front_m.png')">Chest Front </a></li>
						<li><a href="javascript:loadAnnotation('chest_heart.png')">Chest Heart</a></li>
						<li><a href="javascript:loadAnnotation('chest_l_side_m.png')">Chest L Side </a></li>
						<li><a href="javascript:loadAnnotation('chest_r_side_m.png')">Chest R Side </a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Ear&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('r_ear.png')">Right Ear</a></li>
						<li><a href="javascript:loadAnnotation('l_ear.png')">Left Ear</a></li>
						<li><a href="javascript:loadAnnotation('r_tympanic_membrane.png')">Right Tympanic Membrane</a></li>
						<li><a href="javascript:loadAnnotation('l_tympanic_membrane.png')">Left Tympanic Membrane</a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Eye&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('eyes.png')">Eyes</a></li>
						<li><a href="javascript:loadAnnotation('r_eye.png')">Right Eye</a></li>
						<li><a href="javascript:loadAnnotation('r_fundus.png')">Right Fundus</a></li>
						<li><a href="javascript:loadAnnotation('l_eye.png')">Left Eye</a></li>
						<li><a href="javascript:loadAnnotation('l_fundus.png')">Left Fundus</a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Face&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('face_m.png')">Face</a></li>
						<li><a href="javascript:loadAnnotation('face_r_m.png')">Right Face</a></li>
						<li><a href="javascript:loadAnnotation('face_l_m.png')">Left Face</a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<button class='btn btn-primary btn-sm' onclick="loadAnnotation('genitalia_m.png')">Genital</button>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Limb&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('r_arm.png')">Right Arm</a></li>
						<li><a href="javascript:loadAnnotation('r_hand.png')">Right Hand</a></li>
						<li><a href="javascript:loadAnnotation('r_leg.png')">Right Leg</a></li>
						<li><a href="javascript:loadAnnotation('l_arm.png')">Left Arm</a></li>
						<li><a href="javascript:loadAnnotation('l_hand.png')">Left Hand</a></li>
						<li><a href="javascript:loadAnnotation('l_leg.png')">Left Leg</a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Mouth&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('open_mouth_down.png')">Tongue Down</a></li>
						<li><a href="javascript:loadAnnotation('open_mouth_up.png')">Tongue Up</a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Neck&nbsp;
						<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li><a href="javascript:loadAnnotation('neck_front.png')">Neck Front</a></li>
						<li><a href="javascript:loadAnnotation('r_neck_m.png')">Right Neck </a></li>
						<li><a href="javascript:loadAnnotation('l_neck_m.png')">Left Neck </a></li>
				</ul>
		</div>
	</div>

	<div class="form-group">
		<button class='btn btn-primary btn-sm' onclick="loadAnnotation('rectal_m.png')">Rectal</button>
	</div>
-->
@endif
