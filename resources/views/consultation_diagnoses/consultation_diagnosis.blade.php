
<h1>Diagnoses</h1>
<br>
	<div class='form-group  @if ($errors->has('diagnosis_clinical')) has-error @endif'>
        {{ Form::label('diagnosis_clinical', 'Diagnosis',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('diagnosis_clinical', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('diagnosis_clinical')) <p class="help-block">{{ $errors->first('diagnosis_clinical') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('diagnosis_type')) has-error @endif'>
        {{ Form::label('diagnosis_type', 'Type',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
     		{{ Form::select('diagnosis_type', $diagnosis_type,null, ['class'=>'form-control','maxlength'=>'1']) }}
            @if ($errors->has('diagnosis_type')) <p class="help-block">{{ $errors->first('diagnosis_type') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/consultation_diagnoses" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
            {{ Form::hidden('consultation_id', $consultation->consultation_id, ['class'=>'form-control','placeholder'=>'',]) }}
