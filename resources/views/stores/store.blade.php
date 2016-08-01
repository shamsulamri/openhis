
    <div class='form-group  @if ($errors->has('store_name')) has-error @endif'>
        <label for='store_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('store_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('store_name')) <p class="help-block">{{ $errors->first('store_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_receiving')) has-error @endif'>
        {{ Form::label('store_receiving', 'Receiving Store',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('store_receiving', '1') }}
            @if ($errors->has('store_receiving')) <p class="help-block">{{ $errors->first('store_receiving') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/stores" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
