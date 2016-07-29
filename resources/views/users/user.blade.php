
    <div class='form-group  @if ($errors->has('name')) has-error @endif'>
        <label for='name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('username')) has-error @endif'>
        <label for='username' class='col-sm-2 control-label'>Username<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('username', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('username')) <p class="help-block">{{ $errors->first('username') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('author_id')) has-error @endif'>
        <label for='author_id' class='col-sm-2 control-label'>Authorization<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('author_id', $authorizations,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('author_id')) <p class="help-block">{{ $errors->first('author_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/users" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>