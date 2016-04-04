
    <div class='form-group  @if ($errors->has('relation_name')) has-error @endif'>
        <label for='relation_name' class='col-sm-2 control-label'>relation_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('relation_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('relation_name')) <p class="help-block">{{ $errors->first('relation_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/relationships" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>