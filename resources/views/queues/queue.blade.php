	<div class='form-group'>
        {{ Form::label('Current', 'Current',['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9">
            {{ Form::label('current', $queue->location->location_name, ['class'=>'form-control','placeholder'=>'',]) }}
		</div>
	</div>

    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        {{ Form::label('Location', empty($refer)?'New':'Refer To',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('location_code', $location, $queue->location_code, ['class'=>'form-control']) }}
        </div>

    </div>

@if (empty($refer)) 
	<div class='form-group'>
        {{ Form::label('Description', 'Queue Number',['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9">
            {{ Form::text('encounter_description', $queue->encounter->encounter_description, ['class'=>'form-control','placeholder'=>'',]) }}
		</div>
	</div>
@else
	<div class='form-group'>
        {{ Form::label('Queue', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9">
            {{ Form::text('queue_description', $queue->queue_description, ['class'=>'form-control','placeholder'=>'',]) }}
		</div>
	</div>
@endif


	{{ Form::hidden('encounter_id', null) }}


