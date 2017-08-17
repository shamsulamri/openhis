
    <div class='form-group  @if ($errors->has('drug_code')) has-error @endif'>
        {{ Form::label('drug_code', 'drug_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('drug_code', $drug,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('drug_code')) <p class="help-block">{{ $errors->first('drug_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('indication_code')) has-error @endif'>
        {{ Form::label('indication_code', 'indication_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('indication_code', $indication,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('indication_code')) <p class="help-block">{{ $errors->first('indication_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/drug_diseases" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
