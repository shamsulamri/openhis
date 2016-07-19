
    <div class='form-group  @if ($errors->has('loan_name')) has-error @endif'>
        <label for='loan_name' class='col-sm-2 control-label'>loan_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('loan_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('loan_name')) <p class="help-block">{{ $errors->first('loan_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/loan_statuses" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
