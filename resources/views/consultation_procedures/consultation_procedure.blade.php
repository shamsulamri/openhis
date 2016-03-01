
    <div class='form-group  @if ($errors->has('procedure_description')) has-error @endif'>
        {{ Form::label('procedure_description', 'procedure_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('procedure_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('procedure_description')) <p class="help-block">{{ $errors->first('procedure_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('procedure_is_principal')) has-error @endif'>
        {{ Form::label('procedure_is_principal', 'procedure_is_principal',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('procedure_is_principal', '1') }}
            @if ($errors->has('procedure_is_principal')) <p class="help-block">{{ $errors->first('procedure_is_principal') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('consultation_id')) has-error @endif'>
        <label for='consultation_id' class='col-sm-2 control-label'>consultation_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('consultation_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('consultation_id')) <p class="help-block">{{ $errors->first('consultation_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/consultation_procedures" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
