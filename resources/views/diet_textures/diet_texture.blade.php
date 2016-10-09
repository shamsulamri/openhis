
    <div class='form-group  @if ($errors->has('diet_code')) has-error @endif'>
        <label for='diet_code' class='col-sm-3 control-label'>Diet<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('diet_code', $diet,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('diet_code')) <p class="help-block">{{ $errors->first('diet_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('texture_name')) has-error @endif'>
        <label for='texture_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('texture_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('texture_name')) <p class="help-block">{{ $errors->first('texture_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/diet_textures" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
