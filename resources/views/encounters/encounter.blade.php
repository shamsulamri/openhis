
<!--
<h4>
<ul class="nav nav-tabs nav-justified">
  <li role="presentation" class="active"><a href="#">Step 1: Encounter</a></li>
  <li role="presentation" class='disabled'><a href="#">&nbsp;</a></li>
  <li role="presentation" class='disabled'><a href="#">&nbsp;</a></li>
</ul>
</h4>
-->

	<div class="row">
		<div class="col-xs-6">

		<h3>Billing Information</h3>

	<hr>

    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        {{ Form::label('Type', 'Type',['class'=>'col-sm-4 control-label']) }}
        <div class='col-sm-8'>
            {{ Form::select('type_code', $patient_type, 'public', ['id'=>'type_code','onchange'=>'checkPatientType()','class'=>'form-control','maxlength'=>'20']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_code')) has-error @endif'>
        {{ Form::label('sponsor_code', 'Panel',['class'=>'col-sm-4 control-label']) }}
        <div class='col-sm-8'>
            {{ Form::select('sponsor_code', $sponsor, null, ['id'=>'sponsor_code', 'class'=>'form-control','onchange'=>'sponsorChanged()']) }}
            @if ($errors->has('sponsor_code')) <p class="help-block">{{ $errors->first('sponsor_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('entitlement_code')) has-error @endif'>
        {{ Form::label('entitlement_code', 'Entitlement',['class'=>'col-sm-4 control-label']) }}
        <div class='col-sm-8'>
            {{ Form::select('entitlement_code', $entitlement, null, ['id'=>'entitlement_code', 'class'=>'form-control']) }}
            @if ($errors->has('entitlement_code')) <p class="help-block">{{ $errors->first('entitlement_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('entitlement_code')) has-error @endif'>
		{{ Form::label('', '',['class'=>'col-sm-4 control-label']) }}
        <div class='col-sm-8'>
			<label id='sponsor_description' class='form-control'></label>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-4 control-label'>Membership</label>
        <div class='col-sm-8'>
            {{ Form::text('sponsor_id', null, ['id'=>'sponsor_id','class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('sponsor_id')) <p class="help-block">{{ $errors->first('sponsor_id') }}</p> @endif
			<small>Employee, insurance or third party payor identification stated above</small>
        </div>
    </div>

        </div>
        <div class='col-xs-6'>

	<h3>Encounter</h3>
	<hr>
	<div class='form-group  @if ($errors->has('encounter_code')) has-error @endif'>
        <label for='encounter_code' class='col-sm-4 control-label'>Encounter<span style='color:red;'> *</span></label>
        <div class='col-sm-8'>
			{{ Form::select('encounter_code', $encounter_type, $encounter_code, ['id'=>'encounter','class'=>'form-control','onchange'=>'checkTriage()']) }}
				<small>Define the encounter nature of the patient</small>
            @if ($errors->has('encounter_code')) <p class="help-block">{{ $errors->first('encounter_code') }}</p> @endif
        </div>
    </div>

	 <div class='form-group  @if ($errors->has('triage_code')) has-error @endif'>
        {{ Form::label('Triage', 'Triage',['class'=>'col-sm-4 control-label']) }}
        <div class='col-sm-8'>
            {{ Form::select('triage_code', $triage, null, ['id'=>'triage','onchange'=>'checkTriage()','class'=>'form-control','maxlength'=>'20']) }}
			<small>For emergency cases only</small>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
        {{ Form::label('Location', Config::get('host.label_location'),['class'=>'col-sm-4 control-label']) }}
        <div class='col-sm-8'>
            {{ Form::select('location_code', [], null, ['id'=>'location_code','onchange'=>'','class'=>'form-control']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('encounter_description')) has-error @endif'>
        {{ Form::label('Description', Config::get('host.label_description_label'), ['class'=>'col-sm-4 control-label']) }}
        <div class='col-sm-8'>
            {{ Form::text('encounter_description', null, ['id'=>'encounter_description','placeholder'=>Config::get('host.label_description_placeholder'),'class'=>'form-control']) }}
        </div>
    </div>
        </div>
    </div>

<div class="target">
	<br>
		<h3>{{ Config::get('host.label_inpatient') }}</h3>
	<hr>

    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        {{ Form::label('Ward', Config::get('host.label_ward'),['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('ward_code', $wards, null, ['id'=>'ward_code','onchange'=>'wardChanged()','class'=>'form-control']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        {{ Form::label('Class', 'Class',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('class_code', $classes, null, ['id'=>'class_code','onchange'=>'classChanged()','class'=>'form-control']) }}
        </div>
    </div>

    <div class='form-group'>
        {{ Form::label('Block', 'Block Room',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			{{ Form::checkbox('block_room', '1', null, ['id'=>'block_room','onclick'=>'setBlockRoom()']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bed_code')) has-error @endif'>
        <label for='bed_code' class='col-sm-3 control-label'>Bed<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('bed_code', [], null, ['id'=>'bed_code','onchange'=>'','class'=>'form-control']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('admission_code')) has-error @endif'>
        {{ Form::label('admission_code', 'Admission Type',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('admission_code', $admission_type,null, ['id'=>'admission_code','class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('admission_code')) <p class="help-block">{{ $errors->first('admission_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('referral_code')) has-error @endif'>
        {{ Form::label('referral_code', 'Referral',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('referral_code', $referral, null, ['id'=>'referral_code','class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('referral_code')) <p class="help-block">{{ $errors->first('referral_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        <label for='user_id' class='col-sm-3 control-label'>{{ Config::get('host.label_consultant') }}<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('user_id', $consultants,null, ['id'=>'user_id','class'=>'form-control']) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
			<small>Not required for observation and mortality cases</small>
        </div>
    </div>

	<!--
    <div class='form-group  @if ($errors->has('team_code')) has-error @endif'>
        <label for='team_code' class='col-sm-3 control-label'>Consultant/Team<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('team_code', $teams,null, ['id'=>'team_code','class'=>'form-control']) }}
            @if ($errors->has('team_code')) <p class="help-block">{{ $errors->first('team_code') }}</p> @endif
			<small>Not required for death cases</small>
        </div>
    </div>
	-->
</div>
	{{ Form::hidden('patient_id', $patient->patient_id) }}
	{{ Form::hidden('order_id', $order_id) }}
	@if ($bed_booking)
	{{ Form::hidden('book_id', $bed_booking->book_id) }}
	@endif
	@if ($appointment)
	{{ Form::hidden('appointment_id', $appointment->appointment_id) }}
	@endif

<script>
	document.getElementById('admission_code').disabled = true;
	document.getElementById('referral_code').disabled = true;
	document.getElementById('user_id').disabled = true;
	document.getElementById('triage').disabled = true;
	document.getElementById('sponsor_code').disabled = true;
	document.getElementById('entitlement_code').disabled = true;
	document.getElementById('sponsor_id').disabled = true;
	document.getElementById('location_code').disabled = true;
	document.getElementById('ward_code').disabled = true;
	document.getElementById('bed_code').disabled = true;

	function checkTriage() {
		triage = document.getElementById('triage');
		encounter = document.getElementById('encounter').value;
		if (encounter=='emergency') {
				triage.disabled=false;
		} else {
				triage.value = '';
				triage.disabled=true;
		}
				
		encounterChanged(encounter);
	}

	function setBlockRoom() {
			classChanged();
	}

	function checkPatientType() {
		type = document.getElementById('type_code').value;
		if (type == 'public') {
			document.getElementById('sponsor_code').value = '';
			document.getElementById('entitlement_code').value = '';
			document.getElementById('sponsor_id').value = '';
			document.getElementById('sponsor_code').disabled = true;
			document.getElementById('entitlement_code').disabled = true;
			document.getElementById('sponsor_id').disabled = true;
			document.getElementById("sponsor_description").innerHTML = "";
		} else {
			document.getElementById('sponsor_code').disabled = false;
			document.getElementById('entitlement_code').disabled = false;
			document.getElementById('sponsor_id').disabled = false;
		}
	}

	function disableInputs(value) {
			document.getElementById('sponsor_code').disabled = value;
			document.getElementById('sponsor_id').disabled = value;
			document.getElementById('admission_code').disabled = value;
			document.getElementById('referral_code').disabled = value;
			document.getElementById('user_id').disabled = value;
			document.getElementById('triage').disabled = value;
			document.getElementById('sponsor_code').disabled = value;
			document.getElementById('sponsor_id').disabled = value;
			document.getElementById('location_code').disabled = value;
			document.getElementById('ward_code').disabled = value;
			document.getElementById('bed_code').disabled = value;
			document.getElementById('class_code').disabled = value;
			document.getElementById('block_room').disabled = value;
	}

	checkPatientType();

	function encounterChanged(encounterCode) {
			locations = [
				@foreach($locations as $location)
					'{{ $location->encounter_code }}:{{ $location->location_code }}:{{ $location->location_name }}',
				@endforeach
			]

			document.getElementById('location_code').value = '';
			document.getElementById('ward_code').value = '';
			document.getElementById('bed_code').value = '';
			document.getElementById('admission_code').value = '';
			document.getElementById('referral_code').value = '';
			document.getElementById('user_id').value = '';
			document.getElementById('block_room').checked = false;

			disableInputs(true);
			triage = document.getElementById('triage');
			encounter = document.getElementById('encounter').value;
			if (encounter=='emergency') {
					hide(document.querySelectorAll('.target'));
						triage.disabled=false;
			} else {
						triage.value = '';
						triage.disabled=true;
			}

			if (encounterCode == 'outpatient' || encounterCode == 'walkin' || encounterCode == 'drive_thru') {
					hide(document.querySelectorAll('.target'));
					document.getElementById('location_code').disabled = false;
			} 
			
			if (encounterCode=='mortuary') {
					show(document.querySelectorAll('.target'));
					document.getElementById('ward_code').disabled = false;
					document.getElementById('bed_code').disabled = false;
					var wardSelect = document.getElementById('ward_code');
					addList(wardSelect,'mortuary', 'Mortuary');
					document.getElementById('ward_code').value = 'mortuary';
					document.getElementById('ward_code').disabled = true;
					document.getElementById('class_code').value = 'undefined';
					document.getElementById('class_code').disabled = true;
					wardChanged()
			}

			if (encounterCode=='daycare') {
					show(document.querySelectorAll('.target'));
					document.getElementById('referral_code').disabled = false;
					document.getElementById('user_id').disabled = false;
					document.getElementById('ward_code').value = 'daycare';
					document.getElementById('bed_code').disabled = false;
					document.getElementById('admission_code').value = 'scheduled';
					document.getElementById('referral_code').disabled = true;

					var wardSelect = document.getElementById('ward_code');
					addList(wardSelect,'daycare', 'Daycare');

					document.getElementById('ward_code').value = 'daycare';
					wardChanged();
			}

			if (encounterCode=='inpatient') {
					show(document.querySelectorAll('.target'));
					document.getElementById('admission_code').disabled = false;
					document.getElementById('referral_code').disabled = false;
					document.getElementById('user_id').disabled = false;
					document.getElementById('ward_code').disabled = false;
					document.getElementById('bed_code').disabled = false;
					document.getElementById('class_code').disabled = false;
					document.getElementById('block_room').disabled = false;
					wardChanged()
					removeItemList(document.getElementById('ward_code'),'daycare');
					removeItemList(document.getElementById('ward_code'),'mortuary');
					removeItemList(document.getElementById('ward_code'),'observation');
			}

			if (encounterCode=='emergency') {
					document.getElementById('ward_code').value = '';
					document.getElementById('bed_code').value = '';
					if (document.getElementById('triage').value == 'green') {
						hide(document.querySelectorAll('.target'));
						document.getElementById('location_code').disabled = false;
					} else {
						if (document.getElementById('triage').value == 'yellow') {
									var wardSelect = document.getElementById('ward_code');
									addList(wardSelect,'observation', 'Observation');
									show(document.querySelectorAll('.target'));
									if (document.getElementById('triage').value != '') {
										document.getElementById('ward_code').value = 'observation';
										document.getElementById('bed_code').disabled = false;
										document.getElementById('ward_code').disabled = true;
										wardChanged();
									}
								checkPatientType();
								return;
						}
					}
					hide(document.querySelectorAll('.target'));
					document.getElementById('location_code').disabled = false;

			}

			checkPatientType();

			var locationSelect = document.getElementById('location_code');
			clearList(locationSelect);

			if (document.getElementById('location_code').disabled == false) {
			for (var i=0;i<locations.length;i++) {
					values = locations[i].split(":")
					if (encounter==values[0]) {
							addList(locationSelect,values[1], values[2]);
					}
			}
			}

	}

	function wardChanged() {
			wardCode = document.getElementById('ward_code').value;

			classes = [
				@foreach($ward_classes as $class)
					'{{ $class->ward_code }}:{{ $class->class_code }}:{{ $class->wardClass->class_name }}',
				@endforeach
			]

			var bedSelect = document.getElementById('bed_code');
			var classSelect = document.getElementById('class_code');
			clearList(bedSelect);
			clearList(classSelect);

			for (var i=0;i<classes.length;i++) {
					values = classes[i].split(":")
					if (wardCode==values[0]) {
							addList(classSelect,values[1], values[2]);
					}
			}

			classChanged();
	}

	function sponsorChanged() {
			var entitlementSelect = document.getElementById('entitlement_code');
			sponsorCode = document.getElementById('sponsor_code').value;

			var entitlements = {
				@foreach ($sponsors as $sponsor)
					'{{ $sponsor->sponsor_code }}':'{{ $sponsor->entitlement_code }}',
				@endforeach
			}

			var descriptions = {
				@foreach ($sponsors as $sponsor)
					'{{ $sponsor->sponsor_code }}':'{{ $sponsor->sponsor_description }}',
				@endforeach
			}
	  		entitlementSelect.value = entitlements[sponsorCode];
			document.getElementById("sponsor_description").innerHTML = descriptions[sponsorCode];
	}

	function classChanged() {
			wardCode = document.getElementById('ward_code').value;
			classCode = document.getElementById('class_code').value;
			beds = [
				@foreach($beds as $bed)
					'{{ $bed->bed_code }}:{{ $bed->class_code }}:{{ $bed->ward_code }}:{{ $bed->bed_name }}:{{ $bed->room_code }}',
				@endforeach
			]

			empty_rooms = [
			@foreach ($empty_rooms as $room)
				'{{ $room->room_code }}',
			@endforeach
			]
			var bedSelect = document.getElementById('bed_code');

			var isBlock = document.getElementById('block_room').checked;
			if (isBlock) {

			}
			clearList(bedSelect);
			for (var i=0;i<beds.length;i++) {
					values = beds[i].split(":")
					if (wardCode==values[2] && classCode==values[1]) {
							if (!isBlock) {
									addList(bedSelect,values[0], values[3]);
							} else {
									if (empty_rooms.indexOf(values[4])>0) {
										addList(bedSelect,values[0], values[3]);
									}
							}
					}
			}
	}

	function clearList(selectedList) {
		var i;
		for(i=selectedList.options.length-1;i>=0;i--)
		{
			selectedList.remove(i);
		}
	}

	function addList(selectedList, value, text ) {
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value;

		selectedList.options.add(optn);
	}  

	function removeItemList(selectedList, key) {
	  		for (var i=0; i<selectedList.length; i++){
				if (selectedList.options[i].value == key )
							 selectedList.remove(i);
		  	}
	}

	function postForm() {
			disableInputs(false);
			document.getElementById('myForm').submit();
	}


	hide(document.querySelectorAll('.target'));

	function hide (elements) {
			  elements = elements.length ? elements : [elements];
			    for (var index = 0; index < elements.length; index++) {
						    elements[index].style.display = 'none';
							  }
	}

	function show (elements, specifiedDisplay) {
			  var computedDisplay, element, index;

			    elements = elements.length ? elements : [elements];
			    for (index = 0; index < elements.length; index++) {
						    element = elements[index];

							    element.style.display = '';
							    computedDisplay = window.getComputedStyle(element, null).getPropertyValue('display');

								    if (computedDisplay === 'none') {
											        element.style.display = specifiedDisplay || 'block';
													    }
								  }
	}

	
	checkTriage();

	@if (!empty($bed_booking))
	document.getElementById('ward_code').value = '{{ $bed_booking->ward_code }}';
	wardChanged();	
	document.getElementById('class_code').value = '{{ $bed_booking->class_code }}';
	classChanged();
	document.getElementById('bed_code').value = '{{ $bed_booking->bed_code }}';
	document.getElementById('admission_code').value = 'scheduled';
	document.getElementById('user_id').value = '{{ $bed_booking->user_id }}';
	@endif

	@if (!empty($appointment))
			document.getElementById('location_code').value = '{{ $encounter->location_code }}';
	@endif

	@if (!empty($location_code))
			document.getElementById('location_code').value = '{{ $location_code }}';
	@endif
</script>
