
    <div class='form-group  @if ($errors->has('age_amount')) has-error @endif'>
        <label for='age_amount' class='col-sm-2 control-label'>age_amount<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('age_amount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('age_amount')) <p class="help-block">{{ $errors->first('age_amount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('age_days')) has-error @endif'>
        <label for='age_days' class='col-sm-2 control-label'>age_days<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('age_days', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('age_days')) <p class="help-block">{{ $errors->first('age_days') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('age_group')) has-error @endif'>
        <label for='age_group' class='col-sm-2 control-label'>age_group<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('age_group', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('age_group')) <p class="help-block">{{ $errors->first('age_group') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/bill_agings" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
