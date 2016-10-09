
    <div class='form-group  @if ($errors->has('triage_name')) has-error @endif'>
        <label for='triage_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('triage_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('triage_name')) <p class="help-block">{{ $errors->first('triage_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('triage_color')) has-error @endif'>
        <label for='triage_color' class='col-sm-3 control-label'>Colour<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('triage_color', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('triage_color')) <p class="help-block">{{ $errors->first('triage_color') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('triage_position')) has-error @endif'>
        {{ Form::label('triage_position', 'Position',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('triage_position', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('triage_position')) <p class="help-block">{{ $errors->first('triage_position') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/triages" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
