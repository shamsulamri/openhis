
    <div class='form-group  @if ($errors->has('part')) has-error @endif'>
        <label for='part' class='col-sm-2 control-label'>part<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('part', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('part')) <p class="help-block">{{ $errors->first('part') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('header')) has-error @endif'>
        <label for='header' class='col-sm-2 control-label'>header<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('header', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('header')) <p class="help-block">{{ $errors->first('header') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sub_header')) has-error @endif'>
        <label for='sub_header' class='col-sm-2 control-label'>sub_header<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('sub_header', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('sub_header')) <p class="help-block">{{ $errors->first('sub_header') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('description')) has-error @endif'>
        <label for='description' class='col-sm-2 control-label'>description<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('value')) has-error @endif'>
        <label for='value' class='col-sm-2 control-label'>value<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('value', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('value')) <p class="help-block">{{ $errors->first('value') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('value2')) has-error @endif'>
        <label for='value2' class='col-sm-2 control-label'>value2<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('value2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('value2')) <p class="help-block">{{ $errors->first('value2') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/fee_schedules" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
