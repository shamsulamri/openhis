
    <div class='form-group  @if ($errors->has('patient_id')) has-error @endif'>
        <label for='patient_id' class='col-sm-2 control-label'>patient_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('patient_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_id')) <p class="help-block">{{ $errors->first('patient_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('history_code')) has-error @endif'>
        <label for='history_code' class='col-sm-2 control-label'>history_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('history_code', $history,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('history_code')) <p class="help-block">{{ $errors->first('history_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('history_note')) has-error @endif'>
        <label for='history_note' class='col-sm-2 control-label'>history_note<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('history_note', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('history_note')) <p class="help-block">{{ $errors->first('history_note') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/consultation_histories" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
