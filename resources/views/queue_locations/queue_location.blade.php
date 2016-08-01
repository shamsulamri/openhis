
    <div class='form-group  @if ($errors->has('location_name')) has-error @endif'>
        <label for='location_name' class='col-sm-3 control-label'>location_name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('location_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('location_name')) <p class="help-block">{{ $errors->first('location_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('location_is_pool')) has-error @endif'>
        {{ Form::label('location_is_pool', 'location_is_pool',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('location_is_pool', '1') }}
            @if ($errors->has('location_is_pool')) <p class="help-block">{{ $errors->first('location_is_pool') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('department_code')) has-error @endif'>
        {{ Form::label('department_code', 'department_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('department_code', $department,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('department_code')) <p class="help-block">{{ $errors->first('department_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('encounter_code')) has-error @endif'>
        {{ Form::label('encounter_code', 'encounter_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('encounter_code', $encounter,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('encounter_code')) <p class="help-block">{{ $errors->first('encounter_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        <label for='user_id' class='col-sm-3 control-label'>user_id</label>
        <div class='col-sm-9'>
            {{ Form::text('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/queue_locations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
