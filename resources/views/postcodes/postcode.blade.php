
    <div class='form-group  @if ($errors->has('STATE')) has-error @endif'>
        {{ Form::label('STATE', 'STATE',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('STATE', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'63']) }}
            @if ($errors->has('STATE')) <p class="help-block">{{ $errors->first('STATE') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('DISTRICT')) has-error @endif'>
        {{ Form::label('DISTRICT', 'DISTRICT',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('DISTRICT', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'63']) }}
            @if ($errors->has('DISTRICT')) <p class="help-block">{{ $errors->first('DISTRICT') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('POSTCODE')) has-error @endif'>
        {{ Form::label('POSTCODE', 'POSTCODE',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('POSTCODE', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('POSTCODE')) <p class="help-block">{{ $errors->first('POSTCODE') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/postcodes" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
