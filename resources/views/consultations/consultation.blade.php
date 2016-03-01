
    <div class='form-group  @if ($errors->has('encounter_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-2 control-label'>encounter_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('encounter_id')) <p class="help-block">{{ $errors->first('encounter_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        <label for='user_id' class='col-sm-2 control-label'>user_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('consultation_status')) has-error @endif'>
        {{ Form::label('consultation_status', 'consultation_status',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
			{{ Form::select('consultation_status', ['0'=>'Open', '1'=>'Close', '2'=>'Pending'], null, ['class'=>'form-control']) }}
            @if ($errors->has('consultation_status')) <p class="help-block">{{ $errors->first('consultation_status') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('consultation_notes')) has-error @endif'>
        {{ Form::label('consultation_notes', 'consultation_notes',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('consultation_notes', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('consultation_notes')) <p class="help-block">{{ $errors->first('consultation_notes') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/consultations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
