
    <div class='form-group  @if ($errors->has('dosage_name')) has-error @endif'>
        <label for='dosage_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('dosage_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('dosage_name')) <p class="help-block">{{ $errors->first('dosage_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('dosage_label')) has-error @endif'>
        {{ Form::label('dosage_label', 'Label',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('dosage_label', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('dosage_label')) <p class="help-block">{{ $errors->first('dosage_label') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/drug_dosages" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
