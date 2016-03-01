
    <div class='form-group  @if ($errors->has('encounter_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-2 control-label'>encounter_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('encounter_id')) <p class="help-block">{{ $errors->first('encounter_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bed_code')) has-error @endif'>
        <label for='bed_code' class='col-sm-2 control-label'>bed_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('bed_code', $bed,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('bed_code')) <p class="help-block">{{ $errors->first('bed_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('admission_code')) has-error @endif'>
        {{ Form::label('admission_code', 'admission_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('admission_code', $admission_type,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('admission_code')) <p class="help-block">{{ $errors->first('admission_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('referral_code')) has-error @endif'>
        {{ Form::label('referral_code', 'referral_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('referral_code', $referral, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('referral_code')) <p class="help-block">{{ $errors->first('referral_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('diet_code')) has-error @endif'>
        {{ Form::label('diet_code', 'diet_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('diet_code', $diet,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('diet_code')) <p class="help-block">{{ $errors->first('diet_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('texture_code')) has-error @endif'>
        {{ Form::label('texture_code', 'texture_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('texture_code', $texture,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('texture_code')) <p class="help-block">{{ $errors->first('texture_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        {{ Form::label('class_code', 'class_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('class_code', $class,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('admission_nbm')) has-error @endif'>
        {{ Form::label('admission_nbm', 'admission_nbm',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('admission_nbm', '1') }}
            @if ($errors->has('admission_nbm')) <p class="help-block">{{ $errors->first('admission_nbm') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/admissions" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
