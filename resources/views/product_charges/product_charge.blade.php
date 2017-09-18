
    <div class='form-group  @if ($errors->has('charge_name')) has-error @endif'>
        <label for='charge_name' class='col-sm-2 control-label'>charge_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('charge_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('charge_name')) <p class="help-block">{{ $errors->first('charge_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('charge_surcharge')) has-error @endif'>
        {{ Form::label('charge_surcharge', 'Surcharge',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('charge_surcharge', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('charge_surcharge')) <p class="help-block">{{ $errors->first('charge_surcharge') }}</p> @endif
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/product_charges" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
