
    <div class='form-group  @if ($errors->has('property_position')) has-error @endif'>
        {{ Form::label('property_name', 'Name',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('property_name', $form_position->property->property_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_position')) has-error @endif'>
        {{ Form::label('property_position', 'Position',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('property_position', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('property_position')) <p class="help-block">{{ $errors->first('property_position') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/form_positions?form_code={{ $form_position->form_code }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('form_code', $form_position->form_code) }}
	{{ Form::hidden('property_code', $form_position->property_code) }}
