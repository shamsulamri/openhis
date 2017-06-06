
    <div class='form-group  @if ($errors->has('therapeutic_name')) has-error @endif'>
        <label for='therapeutic_name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('therapeutic_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('therapeutic_name')) <p class="help-block">{{ $errors->first('therapeutic_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/diet_therapeutics" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
