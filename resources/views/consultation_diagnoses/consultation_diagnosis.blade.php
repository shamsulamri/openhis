
    <div class='form-group  @if ($errors->has('consultation_id')) has-error @endif'>
        <label for='consultation_id' class='col-sm-2 control-label'>consultation_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('consultation_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('consultation_id')) <p class="help-block">{{ $errors->first('consultation_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('diagnosis_type')) has-error @endif'>
        {{ Form::label('diagnosis_type', 'diagnosis_type',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
     		{{ Form::select('diagnosis_type', $diagnosis_type,null, ['class'=>'form-control','maxlength'=>'1']) }}
            @if ($errors->has('diagnosis_type')) <p class="help-block">{{ $errors->first('diagnosis_type') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('diagnosis_clinical')) has-error @endif'>
        {{ Form::label('diagnosis_clinical', 'diagnosis_clinical',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('diagnosis_clinical', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('diagnosis_clinical')) <p class="help-block">{{ $errors->first('diagnosis_clinical') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('diagnosis_is_principal')) has-error @endif'>
        {{ Form::label('diagnosis_is_principal', 'diagnosis_is_principal',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('diagnosis_is_principal', '1') }}
            @if ($errors->has('diagnosis_is_principal')) <p class="help-block">{{ $errors->first('diagnosis_is_principal') }}</p> @endif
        </div>
    </div>


    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/consultation_diagnoses" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
