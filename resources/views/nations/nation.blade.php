
    <div class='form-group  @if ($errors->has('nation_name')) has-error @endif'>
        <label for='nation_name' class='col-sm-2 control-label'>nation_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('nation_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('nation_name')) <p class="help-block">{{ $errors->first('nation_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('nation_nationality')) has-error @endif'>
        <label for='nation_nationality' class='col-sm-2 control-label'>nation_nationality<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('nation_nationality', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('nation_nationality')) <p class="help-block">{{ $errors->first('nation_nationality') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/nations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
