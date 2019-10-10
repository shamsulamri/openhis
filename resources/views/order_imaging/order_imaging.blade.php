
    <div class='form-group  @if ($errors->has('side')) has-error @endif'>
        <label for='side' class='col-sm-2 control-label'>side<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('side', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('side')) <p class="help-block">{{ $errors->first('side') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('region')) has-error @endif'>
        <label for='region' class='col-sm-2 control-label'>region<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('region', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('region')) <p class="help-block">{{ $errors->first('region') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('view')) has-error @endif'>
        <label for='view' class='col-sm-2 control-label'>view<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('view', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('view')) <p class="help-block">{{ $errors->first('view') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/order_imaging" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
