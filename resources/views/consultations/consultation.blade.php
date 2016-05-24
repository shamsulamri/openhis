	<div class='form-group  @if ($errors->has('consultation_notes')) has-error @endif'>
        <div class='col-sm-12'>
            {{ Form::textarea('consultation_notes', null, ['class'=>'form-control','placeholder'=>'Click here to enter notes']) }}
            @if ($errors->has('consultation_notes')) <p class="help-block">{{ $errors->first('consultation_notes') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-12">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

    {{ Form::hidden('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
    {{ Form::hidden('patient_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
    {{ Form::hidden('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
