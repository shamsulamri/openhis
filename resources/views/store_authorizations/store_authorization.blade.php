
    <div class='form-group  @if ($errors->has('author_id')) has-error @endif'>
        <label for='author_id' class='col-sm-3 control-label'>Authorization Group<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('author_id', $authorizations,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('author_id')) <p class="help-block">{{ $errors->first('author_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        <label for='store_code' class='col-sm-3 control-label'>Store<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('store_code', $store,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/store_authorizations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
