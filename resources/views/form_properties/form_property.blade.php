
    <div class='form-group  @if ($errors->has('property_name')) has-error @endif'>
        <label for='property_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('property_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('property_name')) <p class="help-block">{{ $errors->first('property_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_type')) has-error @endif'>
        <label for='property_type' class='col-sm-3 control-label'>Type<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('property_type', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('property_type')) <p class="help-block">{{ $errors->first('property_type') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_unit')) has-error @endif'>
        {{ Form::label('property_unit', 'Unit',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('property_unit', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('property_unit')) <p class="help-block">{{ $errors->first('property_unit') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_limit_1')) has-error @endif'>
        {{ Form::label('property_limit_1', 'Limit 1',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('property_limit_1', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('property_limit_1')) <p class="help-block">{{ $errors->first('property_limit_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_limit_2')) has-error @endif'>
        {{ Form::label('property_limit_2', 'Limit 2',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('property_limit_2', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('property_limit_2')) <p class="help-block">{{ $errors->first('property_limit_2') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_limit_type')) has-error @endif'>
        {{ Form::label('property_limit_type', 'Limt Type',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('property_limit_type', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'1']) }}
            @if ($errors->has('property_limit_type')) <p class="help-block">{{ $errors->first('property_limit_type') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_list')) has-error @endif'>
        {{ Form::label('property_list', 'Property List',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('property_list', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('property_list')) <p class="help-block">{{ $errors->first('property_list') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_shortname')) has-error @endif'>
        {{ Form::label('property_shortname', 'Shortname',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('property_shortname', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('property_shortname')) <p class="help-block">{{ $errors->first('property_shortname') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_system')) has-error @endif'>
        {{ Form::label('property_system', 'Property System',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('property_system', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('property_system')) <p class="help-block">{{ $errors->first('property_system') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_multiline')) has-error @endif'>
        {{ Form::label('property_multiline', 'Multiline',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('property_multiline', '1') }}
            @if ($errors->has('property_multiline')) <p class="help-block">{{ $errors->first('property_multiline') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/form_properties" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
