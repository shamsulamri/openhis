
    <div class='form-group  @if ($errors->has('arrival_description')) has-error @endif'>
        {{ Form::label('bed', 'Bed',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('bed', $encounter->admission->bed->bed_name, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('arrival_description')) has-error @endif'>
        {{ Form::label('room', 'Room',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if ($encounter->admission->bed->room)
            {{ Form::text('room', $encounter->admission->bed->room->room_name, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
			@else
            {{ Form::text('room', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
			@endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('arrival_description')) has-error @endif'>
        {{ Form::label('ward', 'Ward',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('ward', $encounter->admission->bed->ward->ward_name, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('arrival_description')) has-error @endif'>
        {{ Form::label('arrival_description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('arrival_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('arrival_description')) <p class="help-block">{{ $errors->first('arrival_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/admissions" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
            {{ Form::hidden('encounter_id', $ward_arrival->encounter_id, ['class'=>'form-control','placeholder'=>'',]) }}
