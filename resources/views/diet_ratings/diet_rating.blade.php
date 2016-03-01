
    <div class='form-group  @if ($errors->has('rate_name')) has-error @endif'>
        <label for='rate_name' class='col-sm-2 control-label'>rate_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('rate_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('rate_name')) <p class="help-block">{{ $errors->first('rate_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('rate_position')) has-error @endif'>
        {{ Form::label('rate_position', 'rate_position',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('rate_position', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('rate_position')) <p class="help-block">{{ $errors->first('rate_position') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/diet_ratings" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
