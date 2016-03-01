
    <div class='form-group  @if ($errors->has('encounter_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-2 control-label'>encounter_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('encounter_id')) <p class="help-block">{{ $errors->first('encounter_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('mc_start')) has-error @endif'>
        <label for='mc_start' class='col-sm-2 control-label'>mc_start<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('mc_start', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('mc_start')) <p class="help-block">{{ $errors->first('mc_start') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('mc_end')) has-error @endif'>
        <label for='mc_end' class='col-sm-2 control-label'>mc_end<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('mc_end', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('mc_end')) <p class="help-block">{{ $errors->first('mc_end') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('mc_identification')) has-error @endif'>
        <label for='mc_identification' class='col-sm-2 control-label'>mc_identification<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('mc_identification', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('mc_identification')) <p class="help-block">{{ $errors->first('mc_identification') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('mc_notes')) has-error @endif'>
        {{ Form::label('mc_notes', 'mc_notes',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('mc_notes', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('mc_notes')) <p class="help-block">{{ $errors->first('mc_notes') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/medical_certificates" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
