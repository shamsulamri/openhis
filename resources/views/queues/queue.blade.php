<!--
<h4>
<ul class="nav nav-tabs nav-justified">
  <li role="presentation"><a href="/encounters/{{ $encounter->encounter_id }}/edit">Step 1: Encounter</a></li>
  <li role="presentation" class="active"><a href="#">Final: Queue</a></li>
</ul>
</h4>
-->
<div class='page-header'>
	<h2>{{ $encounter->encounterType->encounter_name }}</h2>
</div>
<h4>Select queue location for the patient.</h4>
<br>
    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
		<label for='location_code' class='col-sm-2 control-label'></label>
        <div class='col-sm-10'>
			@foreach($locations as $l)
			<div class='checkbox'>
            {{ Form::radio('location_code', $l->location_code) }} {{ $l->location_name }}
			</div>
			@endforeach
            @if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
        </div>
    </div>


	{{ Form::hidden('encounter_id', null) }}


	<script>
		@if ($queue->queue_id != null)
		$('input[name=location_code]').attr('checked',false);
		@endif

		document.getElementById('save').disabled=true;

		$('input[name=location_code]').change(function(){
			document.getElementById('save').disabled=false;
		});


	</script>
