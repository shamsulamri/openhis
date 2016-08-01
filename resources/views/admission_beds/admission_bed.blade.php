
    <div class='form-group  @if ($errors->has('encounter_code')) has-error @endif'>
        <label for='encounter_code' class='col-sm-3 control-label'>encounter_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('encounter_code', $encounter,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('encounter_code')) <p class="help-block">{{ $errors->first('encounter_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        {{ Form::label('class_code', 'class_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('class_code', $class,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>ward_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('ward_code', $ward,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('room_code')) has-error @endif'>
        {{ Form::label('room_code', 'room_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('room_code', $room,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('room_code')) <p class="help-block">{{ $errors->first('room_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('status_code')) has-error @endif'>
        {{ Form::label('status_code', 'status_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('status_code', $status,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('status_code')) <p class="help-block">{{ $errors->first('status_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bed_name')) has-error @endif'>
        <label for='bed_name' class='col-sm-3 control-label'>bed_name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bed_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('bed_name')) <p class="help-block">{{ $errors->first('bed_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bed_virtual')) has-error @endif'>
        {{ Form::label('bed_virtual', 'bed_virtual',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('bed_virtual', '1') }}
            @if ($errors->has('bed_virtual')) <p class="help-block">{{ $errors->first('bed_virtual') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('gender_code')) has-error @endif'>
        {{ Form::label('gender_code', 'gender_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('gender_code', $gender,null, ['class'=>'form-control','maxlength'=>'1']) }}
            @if ($errors->has('gender_code')) <p class="help-block">{{ $errors->first('gender_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('department_code')) has-error @endif'>
        {{ Form::label('department_code', 'department_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('department_code', $department,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('department_code')) <p class="help-block">{{ $errors->first('department_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/admission_beds" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
