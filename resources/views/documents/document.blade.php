
    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        <label for='type_code' class='col-sm-2 control-label'>Document Type<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
			@can('module-medical-record')
            {{ Form::select('type_code', $document_type,null, ['class'=>'form-control','maxlength'=>'20']) }}
			@else
            {{ Form::label('type_code', $document->document->type_name, ['class'=>'form-control']) }}
			@endcan
            @if ($errors->has('type_code')) <p class="help-block">{{ $errors->first('type_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('document_description')) has-error @endif'>
        {{ Form::label('document_description', 'Description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('document_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('document_description')) <p class="help-block">{{ $errors->first('document_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('document_status')) has-error @endif'>
        <label for='document_status' class='col-sm-2 control-label'>Status<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
		@can('module-medical-record')
            {{ Form::select('document_status', $document_statuses,null, ['class'=>'form-control','maxlength'=>'20']) }}
		@else
            {{ Form::label('document_status', $document->status->status_name, ['class'=>'form-control']) }}
		@endcan
            @if ($errors->has('document_status')) <p class="help-block">{{ $errors->first('document_status') }}</p> @endif
        </div>
    </div>
	
    <div class='form-group  @if ($errors->has('document_location')) has-error @endif'>
        {{ Form::label('document_location', 'Location',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('document_location', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('document_location')) <p class="help-block">{{ $errors->first('document_location') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/documents?patient_mrn={{ $patient->patient_mrn }}" role="button">Cancel</a>
			@can('module-medical-record')
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
			@endcan
        </div>
    </div>

	{{ Form::hidden('patient_mrn', $patient->patient_mrn) }}
