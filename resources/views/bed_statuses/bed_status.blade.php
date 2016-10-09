
    <div class='form-group  @if ($errors->has('status_name')) has-error @endif'>
        <label for='status_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('status_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('status_name')) <p class="help-block">{{ $errors->first('status_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('status_hidden')) has-error @endif'>
        {{ Form::label('status_hidden', 'Hidden',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('status_hidden', '1') }}
            @if ($errors->has('status_hidden')) <p class="help-block">{{ $errors->first('status_hidden') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/bed_statuses" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
