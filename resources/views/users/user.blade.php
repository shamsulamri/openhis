
    <div class='form-group  @if ($errors->has('name')) has-error @endif'>
        <label for='name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('email')) has-error @endif'>
        <label for='email' class='col-sm-3 control-label'>Email<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('email', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('username')) has-error @endif'>
        <label for='username' class='col-sm-3 control-label'>Username<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('username', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('username')) <p class="help-block">{{ $errors->first('username') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employee_id')) has-error @endif'>
        <label for='employee_id' class='col-sm-3 control-label'>Employee ID<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('employee_id', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('employee_id')) <p class="help-block">{{ $errors->first('employee_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('author_id')) has-error @endif'>
        <label for='author_id' class='col-sm-3 control-label'>Authorization<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('author_id', $authorizations,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('author_id')) <p class="help-block">{{ $errors->first('author_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/users" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
