
    <div class='form-group  @if ($errors->has('contamination_name')) has-error @endif'>
        <label for='contamination_name' class='col-sm-2 control-label'>contamination_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('contamination_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('contamination_name')) <p class="help-block">{{ $errors->first('contamination_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/diet_contaminations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
