	<div class='form-group'>
        {{ Form::label('Current', 'Current',['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9">
            {{ Form::label('current', $queue->location->location_name, ['class'=>'form-control','placeholder'=>'',]) }}
		</div>
	</div>

    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        {{ Form::label('Location', 'New',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
		@if ($queue->encounter->consultation->count() == 0)
            {{ Form::select('location_code', $location, $queue->location_code, ['class'=>'form-control']) }}
		@else
			{{ Form::label('new', 'Consultation has started. Cannot change queue.', ['class'=>'form-control']) }}
		@endif
        </div>

    </div>

	<div class='form-group'>
        {{ Form::label('Description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9">
            {{ Form::text('encounter_description', $queue->encounter->encounter_description, ['class'=>'form-control','placeholder'=>'',]) }}
		</div>
	</div>


	{{ Form::hidden('encounter_id', null) }}


