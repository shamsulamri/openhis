
    <div class='form-group  @if ($errors->has('admission_id')) has-error @endif'>
        <label for='admission_id' class='col-sm-3 control-label'>admission_id<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('admission_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('admission_id')) <p class="help-block">{{ $errors->first('admission_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('move_from')) has-error @endif'>
        {{ Form::label('move_from', 'move_from',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('move_from', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('move_from')) <p class="help-block">{{ $errors->first('move_from') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('move_to')) has-error @endif'>
        {{ Form::label('move_to', 'move_to',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('move_to', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('move_to')) <p class="help-block">{{ $errors->first('move_to') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('move_date')) has-error @endif'>
        <label for='move_date' class='col-sm-3 control-label'>move_date<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('move_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('move_date')) <p class="help-block">{{ $errors->first('move_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/bed_movements" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
