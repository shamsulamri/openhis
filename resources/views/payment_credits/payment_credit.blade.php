
    <div class='form-group  @if ($errors->has('card_code')) has-error @endif'>
        <label for='card_code' class='col-sm-2 control-label'>card_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('card_code', $card,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('card_code')) <p class="help-block">{{ $errors->first('card_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_number')) has-error @endif'>
        <label for='credit_number' class='col-sm-2 control-label'>credit_number<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'16']) }}
            @if ($errors->has('credit_number')) <p class="help-block">{{ $errors->first('credit_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_expiry_month')) has-error @endif'>
        <label for='credit_expiry_month' class='col-sm-2 control-label'>credit_expiry_month<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_expiry_month', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('credit_expiry_month')) <p class="help-block">{{ $errors->first('credit_expiry_month') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_expiry_year')) has-error @endif'>
        <label for='credit_expiry_year' class='col-sm-2 control-label'>credit_expiry_year<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_expiry_year', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('credit_expiry_year')) <p class="help-block">{{ $errors->first('credit_expiry_year') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_first_name')) has-error @endif'>
        <label for='credit_first_name' class='col-sm-2 control-label'>credit_first_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_first_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('credit_first_name')) <p class="help-block">{{ $errors->first('credit_first_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_last_name')) has-error @endif'>
        <label for='credit_last_name' class='col-sm-2 control-label'>credit_last_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_last_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('credit_last_name')) <p class="help-block">{{ $errors->first('credit_last_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_address_1')) has-error @endif'>
        <label for='credit_address_1' class='col-sm-2 control-label'>credit_address_1<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_address_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('credit_address_1')) <p class="help-block">{{ $errors->first('credit_address_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_address_2')) has-error @endif'>
        <label for='credit_address_2' class='col-sm-2 control-label'>credit_address_2<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_address_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('credit_address_2')) <p class="help-block">{{ $errors->first('credit_address_2') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_city')) has-error @endif'>
        <label for='credit_city' class='col-sm-2 control-label'>credit_city<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('credit_city')) <p class="help-block">{{ $errors->first('credit_city') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_postcode')) has-error @endif'>
        <label for='credit_postcode' class='col-sm-2 control-label'>credit_postcode<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'16']) }}
            @if ($errors->has('credit_postcode')) <p class="help-block">{{ $errors->first('credit_postcode') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('nation_code')) has-error @endif'>
        <label for='nation_code' class='col-sm-2 control-label'>nation_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('nation_code', $nation,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('nation_code')) <p class="help-block">{{ $errors->first('nation_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_phone_number')) has-error @endif'>
        <label for='credit_phone_number' class='col-sm-2 control-label'>credit_phone_number<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_phone_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('credit_phone_number')) <p class="help-block">{{ $errors->first('credit_phone_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/payment_credits" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
