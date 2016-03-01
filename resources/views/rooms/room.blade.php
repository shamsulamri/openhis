
    <div class='form-group  @if ($errors->has('room_name')) has-error @endif'>
        <label for='room_name' class='col-sm-2 control-label'>room_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('room_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('room_name')) <p class="help-block">{{ $errors->first('room_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/rooms" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
