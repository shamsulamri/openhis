
    <div class='form-group  @if ($errors->has('class_name')) has-error @endif'>
        <label for='class_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('class_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('class_name')) <p class="help-block">{{ $errors->first('class_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_position')) has-error @endif'>
        {{ Form::label('class_position', 'Position',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('class_position', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('class_position')) <p class="help-block">{{ $errors->first('class_position') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('diet_code')) has-error @endif'>
        {{ Form::label('diet_code', 'Diet',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('diet_code', $diet,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('diet_code')) <p class="help-block">{{ $errors->first('diet_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/diet_classes" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
