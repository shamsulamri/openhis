
<h2>Newborn Registration</h2>
<br>
 	<div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        <label for='user_id' class='col-sm-2 control-label'>user_id</label>
        <div class='col-sm-10'>
            {{ Form::text('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
        </div>
    </div>

   <div class='form-group  @if ($errors->has('delivery_code')) has-error @endif'>
        {{ Form::label('delivery_code', 'delivery_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('delivery_code', $delivery,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('delivery_code')) <p class="help-block">{{ $errors->first('delivery_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_weight')) has-error @endif'>
        {{ Form::label('newborn_weight', 'newborn_weight',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('newborn_weight', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('newborn_weight')) <p class="help-block">{{ $errors->first('newborn_weight') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_length')) has-error @endif'>
        {{ Form::label('newborn_length', 'newborn_length',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('newborn_length', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('newborn_length')) <p class="help-block">{{ $errors->first('newborn_length') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_head_circumferance')) has-error @endif'>
        {{ Form::label('newborn_head_circumferance', 'newborn_head_circumferance',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('newborn_head_circumferance', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('newborn_head_circumferance')) <p class="help-block">{{ $errors->first('newborn_head_circumferance') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_g6pd')) has-error @endif'>
        {{ Form::label('newborn_g6pd', 'newborn_g6pd',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('newborn_g6pd', '1') }}
            @if ($errors->has('newborn_g6pd')) <p class="help-block">{{ $errors->first('newborn_g6pd') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_hepatitis_b')) has-error @endif'>
        {{ Form::label('newborn_hepatitis_b', 'newborn_hepatitis_b',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('newborn_hepatitis_b', '1') }}
            @if ($errors->has('newborn_hepatitis_b')) <p class="help-block">{{ $errors->first('newborn_hepatitis_b') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_bcg')) has-error @endif'>
        {{ Form::label('newborn_bcg', 'newborn_bcg',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('newborn_bcg', '1') }}
            @if ($errors->has('newborn_bcg')) <p class="help-block">{{ $errors->first('newborn_bcg') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_vitamin_k')) has-error @endif'>
        {{ Form::label('newborn_vitamin_k', 'newborn_vitamin_k',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('newborn_vitamin_k', '1') }}
            @if ($errors->has('newborn_vitamin_k')) <p class="help-block">{{ $errors->first('newborn_vitamin_k') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_apgar')) has-error @endif'>
        {{ Form::label('newborn_apgar', 'newborn_apgar',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('newborn_apgar', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('newborn_apgar')) <p class="help-block">{{ $errors->first('newborn_apgar') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_term')) has-error @endif'>
        {{ Form::label('newborn_term', 'newborn_term',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('newborn_term', '1') }}
            @if ($errors->has('newborn_term')) <p class="help-block">{{ $errors->first('newborn_term') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_thyroid')) has-error @endif'>
        {{ Form::label('newborn_thyroid', 'newborn_thyroid',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('newborn_thyroid', '1') }}
            @if ($errors->has('newborn_thyroid')) <p class="help-block">{{ $errors->first('newborn_thyroid') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('apgar_heart_rate')) has-error @endif'>
        {{ Form::label('apgar_heart_rate', 'apgar_heart_rate',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('apgar_heart_rate', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('apgar_heart_rate')) <p class="help-block">{{ $errors->first('apgar_heart_rate') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('apgar_breathing')) has-error @endif'>
        {{ Form::label('apgar_breathing', 'apgar_breathing',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('apgar_breathing', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('apgar_breathing')) <p class="help-block">{{ $errors->first('apgar_breathing') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('apgar_grimace')) has-error @endif'>
        {{ Form::label('apgar_grimace', 'apgar_grimace',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('apgar_grimace', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('apgar_grimace')) <p class="help-block">{{ $errors->first('apgar_grimace') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('apgar_activity')) has-error @endif'>
        {{ Form::label('apgar_activity', 'apgar_activity',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('apgar_activity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('apgar_activity')) <p class="help-block">{{ $errors->first('apgar_activity') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('apgar_appearance')) has-error @endif'>
        {{ Form::label('apgar_appearance', 'apgar_appearance',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('apgar_appearance', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('apgar_appearance')) <p class="help-block">{{ $errors->first('apgar_appearance') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_gestational_weeks')) has-error @endif'>
        {{ Form::label('newborn_gestational_weeks', 'newborn_gestational_weeks',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('newborn_gestational_weeks', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('newborn_gestational_weeks')) <p class="help-block">{{ $errors->first('newborn_gestational_weeks') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_gestational_days')) has-error @endif'>
        {{ Form::label('newborn_gestational_days', 'newborn_gestational_days',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('newborn_gestational_days', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('newborn_gestational_days')) <p class="help-block">{{ $errors->first('newborn_gestational_days') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('complication_code')) has-error @endif'>
        {{ Form::label('complication_code', 'complication_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('complication_code', $complication,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('complication_code')) <p class="help-block">{{ $errors->first('complication_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('birth_code')) has-error @endif'>
        {{ Form::label('birth_code', 'birth_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('birth_code', $birth,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('birth_code')) <p class="help-block">{{ $errors->first('birth_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/newborns" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
    {{ Form::hidden('encounter_id', null) }}
    {{ Form::hidden('consultation_id', $consultation->consultation_id) }}
