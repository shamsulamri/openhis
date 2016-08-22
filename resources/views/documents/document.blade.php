
    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        <label for='type_code' class='col-sm-3 control-label'>Document Type<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			@can('module-medical-record')
            {{ Form::select('type_code', $document_type,null, ['class'=>'form-control','maxlength'=>'20']) }}
			@else
            {{ Form::label('type_code', $document->document->type_name, ['class'=>'form-control']) }}
			@endcan
            @if ($errors->has('type_code')) <p class="help-block">{{ $errors->first('type_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('document_description')) has-error @endif'>
        {{ Form::label('document_description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('document_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('document_description')) <p class="help-block">{{ $errors->first('document_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('document_status')) has-error @endif'>
        <label for='document_status' class='col-sm-3 control-label'>Status<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
		@can('module-medical-record')
            {{ Form::select('document_status', $document_statuses,null, ['class'=>'form-control','maxlength'=>'20']) }}
		@else
            {{ Form::label('document_status', $document->status->status_name, ['class'=>'form-control']) }}
		@endcan
            @if ($errors->has('document_status')) <p class="help-block">{{ $errors->first('document_status') }}</p> @endif
        </div>
    </div>
	
    <div class='form-group  @if ($errors->has('document_location')) has-error @endif'>
        {{ Form::label('document_location', 'Location',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('document_location', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('document_location')) <p class="help-block">{{ $errors->first('document_location') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('document_uuid')) has-error @endif'>
        {{ Form::label('document_uuid', 'UUID',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			<div class='input-group'>
            {{ Form::label('document_uuid', $document->document_uuid, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
				<span class='input-group-btn'>
				<a class='btn btn-default pull-right' href='{{ Config::get('host.report_server') }}/ReportServlet?report=document_label&id={{ $document->document_id }}'>
						Print Label
				</a>
				</span>
			</div>
        </div>
    </div>


    <div class='form-group  @if ($errors->has('document_location')) has-error @endif'>
        {{ Form::label('attach_file', 'Attach File',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
		<input type='file' name='file' class='inputfile' id='file' onchange='readURL(this);'>
        </div>
    </div>

	@if (!empty($document->document_file))
	<div class='form-group'>
        <div class='col-sm-9 col-sm-offset-3'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('documents/file/'. $document->document_uuid) }}'>View attached file</a>
		</div>
	</div>
	@endif
	
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/documents?patient_mrn={{ $patient->patient_mrn }}" role="button">Cancel</a>
			@can('module-medical-record')
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
			@endcan
        </div>
    </div>

	{{ Form::hidden('patient_mrn', $patient->patient_mrn) }}
	{{ Form::hidden('document_uuid', $document->document_uuid) }}
