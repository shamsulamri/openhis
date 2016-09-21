
    <div class='form-group  @if ($errors->has('sponsor_name')) has-error @endif'>
        <label for='sponsor_name' class='col-sm-2 control-label'>sponsor_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('sponsor_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('sponsor_name')) <p class="help-block">{{ $errors->first('sponsor_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_street_1')) has-error @endif'>
        {{ Form::label('sponsor_street_1', 'sponsor_street_1',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sponsor_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('sponsor_street_1')) <p class="help-block">{{ $errors->first('sponsor_street_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_street_2')) has-error @endif'>
        {{ Form::label('sponsor_street_2', 'sponsor_street_2',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sponsor_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('sponsor_street_2')) <p class="help-block">{{ $errors->first('sponsor_street_2') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_city')) has-error @endif'>
        {{ Form::label('sponsor_city', 'sponsor_city',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sponsor_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('sponsor_city')) <p class="help-block">{{ $errors->first('sponsor_city') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_postcode')) has-error @endif'>
        {{ Form::label('sponsor_postcode', 'sponsor_postcode',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sponsor_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('sponsor_postcode')) <p class="help-block">{{ $errors->first('sponsor_postcode') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_state')) has-error @endif'>
        {{ Form::label('sponsor_state', 'sponsor_state',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sponsor_state', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('sponsor_state')) <p class="help-block">{{ $errors->first('sponsor_state') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_country')) has-error @endif'>
        {{ Form::label('sponsor_country', 'sponsor_country',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sponsor_country', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('sponsor_country')) <p class="help-block">{{ $errors->first('sponsor_country') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_phone')) has-error @endif'>
        {{ Form::label('sponsor_phone', 'sponsor_phone',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sponsor_phone', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('sponsor_phone')) <p class="help-block">{{ $errors->first('sponsor_phone') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/sponsors" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
