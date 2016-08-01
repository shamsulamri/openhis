
    <div class='form-group  @if ($errors->has('patient_id')) has-error @endif'>
        <label for='patient_id' class='col-sm-3 control-label'>patient_id<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('patient_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_id')) <p class="help-block">{{ $errors->first('patient_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('dependant_id')) has-error @endif'>
        <label for='dependant_id' class='col-sm-3 control-label'>dependant_id<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('dependant_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('dependant_id')) <p class="help-block">{{ $errors->first('dependant_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('relation_code')) has-error @endif'>
        <label for='relation_code' class='col-sm-3 control-label'>relation_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('relation_code', $relation,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('relation_code')) <p class="help-block">{{ $errors->first('relation_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/patient_dependants" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
