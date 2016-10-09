
    <div class='form-group  @if ($errors->has('form_code')) has-error @endif'>
        <label for='form_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('form_code', $form,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('form_code')) <p class="help-block">{{ $errors->first('form_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_code')) has-error @endif'>
        <label for='property_code' class='col-sm-3 control-label'>Form property<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('property_code', $property,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('property_code')) <p class="help-block">{{ $errors->first('property_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_position')) has-error @endif'>
        {{ Form::label('property_position', 'Position',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('property_position', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('property_position')) <p class="help-block">{{ $errors->first('property_position') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/form_positions" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
