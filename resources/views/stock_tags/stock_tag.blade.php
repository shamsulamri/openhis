
    <div class='form-group  @if ($errors->has('tag_name')) has-error @endif'>
        <label for='tag_name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('tag_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('tag_name')) <p class="help-block">{{ $errors->first('tag_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('move_code')) has-error @endif'>
        <label for='move_code' class='col-sm-2 control-label'>Movement Type</label>
        <div class='col-sm-10'>
            {{ Form::select('move_code', $stock_movements,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('move_code')) <p class="help-block">{{ $errors->first('move_code') }}</p> @endif
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/stock_tags" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
