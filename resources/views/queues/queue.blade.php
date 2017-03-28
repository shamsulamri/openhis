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
	<div class='form-group'>
        {{ Form::label('Current', 'Current',['class'=>'col-sm-2 control-label']) }}
        <div class="col-sm-6">
            {{ Form::label('current', $queue->location->location_name, ['class'=>'form-control','placeholder'=>'',]) }}
		</div>
	</div>
    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        {{ Form::label('Location', 'New',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-6'>
            {{ Form::select('location_code', $location, $queue->location_code, ['class'=>'form-control']) }}
        </div>
    </div>


	{{ Form::hidden('encounter_id', null) }}


