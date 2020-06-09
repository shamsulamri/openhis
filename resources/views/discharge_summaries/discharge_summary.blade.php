
    <div class='form-group  @if ($errors->has('summary_treatment')) has-error @endif'>
        {{ Form::label('summary_treatment', 'summary_treatment',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('summary_treatment', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('summary_treatment')) <p class="help-block">{{ $errors->first('summary_treatment') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('summary_surgical')) has-error @endif'>
        {{ Form::label('summary_surgical', 'summary_surgical',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('summary_surgical', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('summary_surgical')) <p class="help-block">{{ $errors->first('summary_surgical') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('summary_follow_up')) has-error @endif'>
        {{ Form::label('summary_follow_up', 'summary_follow_up',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('summary_follow_up', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('summary_follow_up')) <p class="help-block">{{ $errors->first('summary_follow_up') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('summary_medication')) has-error @endif'>
        {{ Form::label('summary_medication', 'summary_medication',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('summary_medication', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('summary_medication')) <p class="help-block">{{ $errors->first('summary_medication') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/discharge_summaries" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
