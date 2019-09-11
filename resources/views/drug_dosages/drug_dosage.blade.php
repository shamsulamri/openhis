
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

    <div class='form-group  @if ($errors->has('dosage_count_unit')) has-error @endif'>
        {{ Form::label('dosage_count_unit', 'Count Unit',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('dosage_count_unit', '1',null, ['class'=>'checkbox']) }} Count number of unit to dispense from prescription. Apply to drugs.
            @if ($errors->has('dosage_count_unit')) <p class="help-block">{{ $errors->first('dosage_count_unit') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('dosage_index')) has-error @endif'>
        {{ Form::label('dosage_index', 'Index',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('dosage_index', null, ['class'=>'form-control','placeholder'=>'Item position in list','maxlength'=>'50']) }}
            @if ($errors->has('dosage_index')) <p class="help-block">{{ $errors->first('dosage_index') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/drug_dosages" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
