
    <div class='form-group  @if ($errors->has('parent_name')) has-error @endif'>
        <label for='parent_name' class='col-sm-2 control-label'>parent_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('parent_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('parent_name')) <p class="help-block">{{ $errors->first('parent_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('parent_index')) has-error @endif'>
        <label for='parent_index' class='col-sm-2 control-label'>parent_index<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('parent_index', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('parent_index')) <p class="help-block">{{ $errors->first('parent_index') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/product_category_parents" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
