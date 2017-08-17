
    <div class='form-group  @if ($errors->has('instruction_english')) has-error @endif'>
        {{ Form::label('instruction_english', 'English',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('instruction_english', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('instruction_english')) <p class="help-block">{{ $errors->first('instruction_english') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('instruction_bahasa')) has-error @endif'>
        {{ Form::label('instruction_bahasa', 'Bahasa',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('instruction_bahasa', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('instruction_bahasa')) <p class="help-block">{{ $errors->first('instruction_bahasa') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/drug_instructions" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
