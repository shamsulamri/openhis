
<h1>Clinical Procedures</h1>
<br>
    <div class='form-group  @if ($errors->has('procedure_description')) has-error @endif'>
        <div class='col-sm-12'>
            {{ Form::textarea('procedure_description', null, ['class'=>'form-control', 'rows'=>'5']) }}
            @if ($errors->has('procedure_description')) <p class="help-block">{{ $errors->first('procedure_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('procedure_is_principal')) has-error @endif'>
        <div class='col-sm-12'>
            {{ Form::checkbox('procedure_is_principal', '1') }} Principal procedure
            @if ($errors->has('procedure_is_principal')) <p class="help-block">{{ $errors->first('procedure_is_principal') }}</p> @endif
        </div>
    </div>

	
    <div class='form-group'>
        <div class="col-sm-12">
            <a class="btn btn-default" href="/consultation_procedures" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
            {{ Form::hidden('consultation_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
