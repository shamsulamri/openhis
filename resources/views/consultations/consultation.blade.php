




<div class="row">
	<div class="col-xs-7">
			<div class='form-group'>
				<div class='col-md-12'>
						<h3>Seen by {{ Auth::user()->name }} at {{ DojoUtility::dateTimeReadFormat($consultation->created_at) }} 
						</h3>
				</div>
			</div>
	</div>
	<div class="col-xs-5">
			<div class='form-group'>
				@if ($consultation->encounter->encounter_code == 'emergency')
				{{ Form::label('Triage', 'Triage',['class'=>'col-md-6 control-label']) }}
				<div class='col-md-6'>
						{{ Form::select('triage_code', $triage, $consultation->encounter->triage_code, ['id'=>'triage_code','onchange'=>'checkTriage()',
									'class'=>'form-control','maxlength'=>'20']) }}

				</div>
				@endif
				@if (Auth::user()->author_id == 15)
					<a target="_blank" class='btn btn-success pull-right' href="{{ Config::get('host.report_server')  }}/ReportServlet?report=image_report&id={{ $consultation->consultation_id }}&encounter_id={{ $consultation->encounter_id }}">
				Print
				</a>
				@endif
			</div>
	</div>
</div>
<br>
	<div class='form-group  @if ($errors->has('consultation_notes')) has-error @endif'>
        <div class='col-sm-12'>
            {{ Form::textarea('consultation_notes', null, ['id'=>'consultation_notes', 'tabindex'=>1, 'class'=>'form-control','rows'=>'30', 'style'=>'border:none;font-size: 1.2em']) }}
            @if ($errors->has('consultation_notes')) <p class="help-block">{{ $errors->first('consultation_notes') }}</p> @endif
        </div>
    </div>

    {{ Form::hidden('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
    {{ Form::hidden('patient_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
    {{ Form::hidden('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}

