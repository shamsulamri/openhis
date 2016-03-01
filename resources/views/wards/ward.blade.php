
    <div class='form-group  @if ($errors->has('gender_code')) has-error @endif'>
        {{ Form::label('gender_code', 'gender_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('gender_code', $gender,null, ['class'=>'form-control','maxlength'=>'1']) }}
            @if ($errors->has('gender_code')) <p class="help-block">{{ $errors->first('gender_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('department_code')) has-error @endif'>
        {{ Form::label('department_code', 'department_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('department_code', $department,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('department_code')) <p class="help-block">{{ $errors->first('department_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ward_name')) has-error @endif'>
        <label for='ward_name' class='col-sm-2 control-label'>ward_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('ward_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('ward_name')) <p class="help-block">{{ $errors->first('ward_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/wards" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
