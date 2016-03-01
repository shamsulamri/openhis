
    <div class='form-group  @if ($errors->has('service_name')) has-error @endif'>
        {{ Form::label('service_name', 'service_name',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('service_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('service_name')) <p class="help-block">{{ $errors->first('service_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('department_code')) has-error @endif'>
        <label for='department_code' class='col-sm-2 control-label'>department_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('department_code', $department,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('department_code')) <p class="help-block">{{ $errors->first('department_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_start')) has-error @endif'>
        <label for='service_start' class='col-sm-2 control-label'>service_start<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('service_start', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('service_start')) <p class="help-block">{{ $errors->first('service_start') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_end')) has-error @endif'>
        <label for='service_end' class='col-sm-2 control-label'>service_end<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('service_end', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('service_end')) <p class="help-block">{{ $errors->first('service_end') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_duration')) has-error @endif'>
        {{ Form::label('service_duration', 'service_duration',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('service_duration', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('service_duration')) <p class="help-block">{{ $errors->first('service_duration') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_monday')) has-error @endif'>
        {{ Form::label('service_monday', 'service_monday',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('service_monday', '1') }}
            @if ($errors->has('service_monday')) <p class="help-block">{{ $errors->first('service_monday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_tuesday')) has-error @endif'>
        {{ Form::label('service_tuesday', 'service_tuesday',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('service_tuesday', '1') }}
            @if ($errors->has('service_tuesday')) <p class="help-block">{{ $errors->first('service_tuesday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_wednesday')) has-error @endif'>
        {{ Form::label('service_wednesday', 'service_wednesday',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('service_wednesday', '1') }}
            @if ($errors->has('service_wednesday')) <p class="help-block">{{ $errors->first('service_wednesday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_thursday')) has-error @endif'>
        {{ Form::label('service_thursday', 'service_thursday',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('service_thursday', '1') }}
            @if ($errors->has('service_thursday')) <p class="help-block">{{ $errors->first('service_thursday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_friday')) has-error @endif'>
        {{ Form::label('service_friday', 'service_friday',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('service_friday', '1') }}
            @if ($errors->has('service_friday')) <p class="help-block">{{ $errors->first('service_friday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_saturday')) has-error @endif'>
        {{ Form::label('service_saturday', 'service_saturday',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('service_saturday', '1') }}
            @if ($errors->has('service_saturday')) <p class="help-block">{{ $errors->first('service_saturday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_sunday')) has-error @endif'>
        {{ Form::label('service_sunday', 'service_sunday',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('service_sunday', '1') }}
            @if ($errors->has('service_sunday')) <p class="help-block">{{ $errors->first('service_sunday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        {{ Form::label('user_id', 'user_id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_block_dates')) has-error @endif'>
        {{ Form::label('service_block_dates', 'service_block_dates',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('service_block_dates', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('service_block_dates')) <p class="help-block">{{ $errors->first('service_block_dates') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_status')) has-error @endif'>
        <label for='service_status' class='col-sm-2 control-label'>service_status<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
			{{ Form::select('service_status', ['1'=>'Active', '2'=>'Suspend', '99'=>'Inactive'], null, ['class'=>'form-control']) }}
            @if ($errors->has('service_status')) <p class="help-block">{{ $errors->first('service_status') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/appointment_services" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
