
    <div class='form-group  @if ($errors->has('card_name')) has-error @endif'>
        <label for='card_name' class='col-sm-2 control-label'>card_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('card_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('card_name')) <p class="help-block">{{ $errors->first('card_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/credit_cards" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
