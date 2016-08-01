<!--
<h4>
<ul class="nav nav-tabs nav-justified">
  <li role="presentation"><a href="/encounters/{{ $encounter->encounter_id }}/edit">Step 1: Encounter</a></li>
  <li role="presentation" class="active"><a href="#">Final: Queue</a></li>
</ul>
</h4>
-->
<div class='page-header'>
	<h3>{{ $encounter->encounterType->encounter_name }}</h3>
</div>
<h4>Select queue location</h4>
<br>
			@foreach($locations as $l)
			<div class='checkbox' class='form-control'>
            {{ Form::radio('location_code', $l->location_code) }} {{ $l->location_name }}
			</div>
			@endforeach
    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
		<label for='location_code' class='col-sm-3 control-label'></label>
        <div class='col-sm-9'>
            @if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
        </div>
    </div>


	{{ Form::hidden('encounter_id', null) }}


