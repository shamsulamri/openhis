
    <div class='form-group  @if ($errors->has('group_name')) has-error @endif'>
        <label for='group_name' class='col-sm-2 control-label'>group_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('group_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('group_name')) <p class="help-block">{{ $errors->first('group_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('gl_code')) has-error @endif'>
        <label for='gl_code' class='col-sm-2 control-label'>gl_code</label>
        <div class='col-sm-10'>
            {{ Form::select('gl_code', $gl,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('gl_code')) <p class="help-block">{{ $errors->first('gl_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/product_groups" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
