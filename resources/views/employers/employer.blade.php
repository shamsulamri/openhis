
    <div class='form-group  @if ($errors->has('employer_name')) has-error @endif'>
        <label for='employer_name' class='col-sm-2 control-label'>employer_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('employer_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('employer_name')) <p class="help-block">{{ $errors->first('employer_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employer_street_1')) has-error @endif'>
        {{ Form::label('employer_street_1', 'employer_street_1',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('employer_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('employer_street_1')) <p class="help-block">{{ $errors->first('employer_street_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employer_street_2')) has-error @endif'>
        {{ Form::label('employer_street_2', 'employer_street_2',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('employer_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('employer_street_2')) <p class="help-block">{{ $errors->first('employer_street_2') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employer_city')) has-error @endif'>
        {{ Form::label('employer_city', 'employer_city',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('employer_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('employer_city')) <p class="help-block">{{ $errors->first('employer_city') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employer_postcode')) has-error @endif'>
        {{ Form::label('employer_postcode', 'employer_postcode',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('employer_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('employer_postcode')) <p class="help-block">{{ $errors->first('employer_postcode') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employer_state')) has-error @endif'>
        {{ Form::label('employer_state', 'employer_state',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('employer_state', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('employer_state')) <p class="help-block">{{ $errors->first('employer_state') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employer_country')) has-error @endif'>
        {{ Form::label('employer_country', 'employer_country',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('employer_country', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('employer_country')) <p class="help-block">{{ $errors->first('employer_country') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('employer_phone')) has-error @endif'>
        {{ Form::label('employer_phone', 'employer_phone',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('employer_phone', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('employer_phone')) <p class="help-block">{{ $errors->first('employer_phone') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/employers" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
