
    <div class='form-group  @if ($errors->has('block_name')) has-error @endif'>
        {{ Form::label('block_name', 'block_name',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('block_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('block_name')) <p class="help-block">{{ $errors->first('block_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_date')) has-error @endif'>
        <label for='block_date' class='col-sm-3 control-label'>block_date<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('block_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('block_date')) <p class="help-block">{{ $errors->first('block_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_annually')) has-error @endif'>
        {{ Form::label('block_recur_annually', 'block_recur_annually',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('block_recur_annually', '1') }}
            @if ($errors->has('block_recur_annually')) <p class="help-block">{{ $errors->first('block_recur_annually') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_weekly')) has-error @endif'>
        {{ Form::label('block_recur_weekly', 'block_recur_weekly',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('block_recur_weekly', '1') }}
            @if ($errors->has('block_recur_weekly')) <p class="help-block">{{ $errors->first('block_recur_weekly') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_monthly')) has-error @endif'>
        {{ Form::label('block_recur_monthly', 'block_recur_monthly',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('block_recur_monthly', '1') }}
            @if ($errors->has('block_recur_monthly')) <p class="help-block">{{ $errors->first('block_recur_monthly') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/block_dates" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
