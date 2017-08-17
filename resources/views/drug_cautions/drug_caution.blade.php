
    <div class='form-group  @if ($errors->has('caution_english')) has-error @endif'>
        {{ Form::label('caution_english', 'English',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('caution_english', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('caution_english')) <p class="help-block">{{ $errors->first('caution_english') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('caution_bahasa')) has-error @endif'>
        {{ Form::label('caution_bahasa', 'Bahasa',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('caution_bahasa', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('caution_bahasa')) <p class="help-block">{{ $errors->first('caution_bahasa') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/drug_cautions" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
