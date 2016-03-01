
    <div class='form-group  @if ($errors->has('unit_name')) has-error @endif'>
        <label for='unit_name' class='col-sm-2 control-label'>unit_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('unit_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('unit_name')) <p class="help-block">{{ $errors->first('unit_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('unit_shortname')) has-error @endif'>
        {{ Form::label('unit_shortname', 'unit_shortname',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('unit_shortname', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('unit_shortname')) <p class="help-block">{{ $errors->first('unit_shortname') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('unit_is_decimal')) has-error @endif'>
        {{ Form::label('unit_is_decimal', 'unit_is_decimal',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('unit_is_decimal', '1') }}
            @if ($errors->has('unit_is_decimal')) <p class="help-block">{{ $errors->first('unit_is_decimal') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('unit_drug')) has-error @endif'>
        {{ Form::label('unit_drug', 'unit_drug',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('unit_drug', '1') }}
            @if ($errors->has('unit_drug')) <p class="help-block">{{ $errors->first('unit_drug') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/unit_measures" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
