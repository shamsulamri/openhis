	<div class='form-group'>
        {{ Form::label('Current', 'Current',['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9">
            {{ Form::label('current', $queue->location->location_name, ['class'=>'form-control','placeholder'=>'',]) }}
		</div>
	</div>
    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        {{ Form::label('Location', 'New',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('location_code', $location, $queue->location_code, ['class'=>'form-control']) }}
        </div>
    </div>


	{{ Form::hidden('encounter_id', null) }}


