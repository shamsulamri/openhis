
    <div class='form-group  @if ($errors->has('route_name')) has-error @endif'>
        <label for='route_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('route_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('route_name')) <p class="help-block">{{ $errors->first('route_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('route_label')) has-error @endif'>
        {{ Form::label('route_label', 'Label',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('route_label', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('route_label')) <p class="help-block">{{ $errors->first('route_label') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/drug_routes" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
