
    <div class='form-group  @if ($errors->has('property_name')) has-error @endif'>
        <label for='property_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('property_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('property_name')) <p class="help-block">{{ $errors->first('property_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_shortname')) has-error @endif'>
        {{ Form::label('property_shortname', 'Shortname',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('property_shortname', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('property_shortname')) <p class="help-block">{{ $errors->first('property_shortname') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('property_type')) has-error @endif'>
        <label for='property_type' class='col-sm-3 control-label'>Type<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			{{ Form::select('property_type', $property_types,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('property_type')) <p class="help-block">{{ $errors->first('unit_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
        {{ Form::label('unit_code', 'Unit',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			{{ Form::select('unit_code', $uom,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('unit_code')) <p class="help-block">{{ $errors->first('unit_code') }}</p> @endif
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

	<div class='form-group  @if ($errors->has('system_code')) has-error @endif'>
		{{ Form::label('Property System', 'Property System',['class'=>'col-sm-2 control-label']) }}
		<div class='col-sm-4'>
			{{ Form::select('system_code', $form_system,null, ['class'=>'form-control','maxlength'=>'10']) }}
			@if ($errors->has('system_code')) <p class="help-block">{{ $errors->first('system_code') }}</p> @endif
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

	{{ Form::hidden('form_code', $form_code) }}
