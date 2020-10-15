
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
        <label for='employee_id' class='col-sm-3 control-label'>Employee ID</label>
        <div class='col-sm-9'>
            {{ Form::text('employee_id', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('employee_id')) <p class="help-block">{{ $errors->first('employee_id') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('department_code')) has-error @endif'>
		{{ Form::label('department_code', 'Department',['class'=>'col-sm-3 control-label']) }}
		<div class='col-sm-9'>
			{{ Form::select('department_code', $departments,null, ['class'=>'form-control','maxlength'=>'20', ]) }}
			@if ($errors->has('department_code')) <p class="help-block">{{ $errors->first('department_code') }}</p> @endif
		</div>
	</div>
    <div class='form-group  @if ($errors->has('consultant')) has-error @endif'>
        <label for='consultant' class='col-sm-3 control-label'>Consultant</label>
        <div class='col-sm-9'>
            {{ Form::checkbox('consultant', '1', $user->consultant, ['class'=>'checkbox']) }}
            @if ($errors->has('consultant')) <p class="help-block">{{ $errors->first('consultant') }}</p> @endif
        </div>
    </div>
	<hr>
    <div class='form-group  @if ($errors->has('license_number')) has-error @endif'>
        <label for='license_number' class='col-sm-3 control-label'>License Number<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('license_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('license_number')) <p class="help-block">{{ $errors->first('license_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('tax_number')) has-error @endif'>
        <label for='tax_number' class='col-sm-3 control-label'>Tax Number</label>
        <div class='col-sm-9'>
            {{ Form::text('tax_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('tax_number')) <p class="help-block">{{ $errors->first('tax_number') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('tax_code')) has-error @endif'>
		{{ Form::label('tax_code', 'Tax Code',['class'=>'col-sm-3 control-label']) }}
		<div class='col-sm-9'>
			{{ Form::select('tax_code', $tax_code,null, ['class'=>'form-control','maxlength'=>'20', ]) }}
			@if ($errors->has('tax_code')) <p class="help-block">{{ $errors->first('tax_code') }}</p> @endif
		</div>
	</div>

	<hr>
    <div class='form-group  @if ($errors->has('author_id')) has-error @endif'>
        <label for='author_id' class='col-sm-3 control-label'>User Authorization<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('author_id', $authorizations,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('author_id')) <p class="help-block">{{ $errors->first('author_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_id')) has-error @endif'>
        <label for='service_id' class='col-sm-3 control-label'>Appointment Book</label>
        <div class='col-sm-9'>
            {{ Form::select('service_id', $services,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('service_id')) <p class="help-block">{{ $errors->first('service_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
        {{ Form::label('location_code', 'Queue Location',['class'=>'col-sm-3 control-label']) }}
		<div class='col-sm-9'>
			{{ Form::select('location_code', $location, null, ['class'=>'form-control','maxlength'=>'10']) }}
			@if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
		</div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/users" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
