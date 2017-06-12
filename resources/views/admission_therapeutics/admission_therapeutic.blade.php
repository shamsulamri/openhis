
    <div class='form-group  @if ($errors->has('therapeutic_code')) has-error @endif'>
        <label for='therapeutic_code' class='col-sm-2 control-label'>therapeutic_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('therapeutic_code', $therapeutic,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('therapeutic_code')) <p class="help-block">{{ $errors->first('therapeutic_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('therapeutic_value')) has-error @endif'>
        <label for='therapeutic_value' class='col-sm-2 control-label'>therapeutic_value<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::checkbox('therapeutic_value', '1') }}
            @if ($errors->has('therapeutic_value')) <p class="help-block">{{ $errors->first('therapeutic_value') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/admission_therapeutics" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
