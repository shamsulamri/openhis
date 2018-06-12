
    <div class='form-group  @if ($errors->has('ward_name')) has-error @endif'>
        <label for='ward_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('ward_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('ward_name')) <p class="help-block">{{ $errors->first('ward_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('encounter_code')) has-error @endif'>
        <label for='ward_name' class='col-sm-3 control-label'>Encounter<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('encounter_code', $encounter_type,null, ['class'=>'form-control','maxlength'=>'1']) }}
            @if ($errors->has('encounter_code')) <p class="help-block">{{ $errors->first('encounter_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('department_code')) has-error @endif'>
        <label for='ward_name' class='col-sm-3 control-label'>Department<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('department_code', $department,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('department_code')) <p class="help-block">{{ $errors->first('department_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('gender_code')) has-error @endif'>
        {{ Form::label('gender_code', 'Gender',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('gender_code', $gender,null, ['class'=>'form-control','maxlength'=>'1']) }}
            @if ($errors->has('gender_code')) <p class="help-block">{{ $errors->first('gender_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        {{ Form::label('store_code', 'Store',['class'=>'col-sm-3 control-label']) }}
		<div class='col-sm-9'>
			{{ Form::select('store_code', $store, null, ['class'=>'form-control','maxlength'=>'10']) }}
			@if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
		</div>
    </div>

    <div class='form-group  @if ($errors->has('ward_level')) has-error @endif'>
        {{ Form::label('ward_level', 'Level',['class'=>'col-sm-3 control-label']) }}
		<div class='col-sm-9'>
            {{ Form::text('ward_level', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
			@if ($errors->has('ward_level')) <p class="help-block">{{ $errors->first('ward_level') }}</p> @endif
		</div>
    </div>

    <div class='form-group  @if ($errors->has('ward_omission')) has-error @endif'>
        {{ Form::label('ward_omission', 'Omission',['class'=>'col-sm-3 control-label']) }}
		<div class='col-sm-9'>
            {{ Form::checkbox('ward_omission', '1') }} Hide this ward from admission list
		</div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/wards" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<script>
         $(document).ready(function(){
             $("#form").validate({
                 rules: {
                     ward_level: {
                         number: true
                     },
				 }
             });
        });
	</script>
