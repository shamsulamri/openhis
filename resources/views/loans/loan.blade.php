	
<div class='pull-right'>
<a class='btn btn-primary'  target="_blank" href='{{ Config::get('host.report_server') }}?report=loan_slip&id={{ $loan->loan_id }}'>
Loan Slip
</a>
<a class='btn btn-primary'  target="_blank" href='{{ Config::get('host.report_server') }}?report=exchange_slip&id={{ $loan->loan_id }}'>
Exchange Slip
</a>
<a class='btn btn-primary'  target="_blank" href='{{ Config::get('host.report_server') }}?report=return_slip&id={{ $loan->loan_id }}'>
Return Slip
</a>
</div>
<br>
	<div class='page-header'>
		<h4>Request</h4>
	</div>
	@if ($loan->exchange_id>0)
			<div class='alert alert-warning'>Loan Exchange</div>
	@endif
	@if ($product)
	<div class='form-group'>
		<label for='item_code' class='col-sm-3 control-label'>Product</label>
        <div class='col-sm-9'>
            {{ Form::label('item_name', $product->product_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
	@endif

	@if ($patient)
	<div class='form-group'>
		<label for='item_code' class='col-sm-3 control-label'>Patient</label>
        <div class='col-sm-9'>
            {{ Form::label('item_name', $patient->patient_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
	@endif
	<div class='form-group'>
        <label for='item_code' class='col-sm-3 control-label'>
		@if ($patient)
			MRN
		@else
			Product
		@endif
		</label>
        <div class='col-sm-9'>
			@if ($product)
            {{ Form::label('item_code', $product->product_code, ['class'=>'form-control','placeholder'=>'',]) }}
			@else
            {{ Form::label('item_code', $loan->getItemName($loan->item_code), ['class'=>'form-control','placeholder'=>'',]) }}
			@endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-3 control-label'>Type</label>
        <div class='col-sm-9'>
            {{ Form::label('loan_code', $loan->type->type_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_request_by')) has-error @endif'>
        <label for='loan_request_by' class='col-sm-3 control-label'>Request By</label>
        <div class='col-sm-9'>
            {{ Form::label('loan_request_by', $loan->user->name, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('loan_request_by')) <p class="help-block">{{ $errors->first('loan_request_by') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('created_at')) has-error @endif'>
        <label for='created_at' class='col-sm-3 control-label'>Request Date</label>
        <div class='col-sm-9'>
            {{ Form::label('created_at',  date('d F Y, H:i', strtotime($loan->created_at)) , ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('created_at')) <p class="help-block">{{ $errors->first('item_code') }}</p> @endif
        </div>
    </div>

	@if ($loan->ward_code)
    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Ward</label>
        <div class='col-sm-9'>
            {{ Form::label('ward', $loan->ward->ward_name, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>
	@endif

	@if ($loan->location_code)
    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
        <label for='location_code' class='col-sm-3 control-label'>Clinic</label>
        <div class='col-sm-9'>
            {{ Form::label('location', $loan->location->location_name, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
        </div>
    </div>
	@endif

	@if ($loan->type_code != 'folder')
    <div class='form-group  @if ($errors->has('loan_quantity')) has-error @endif'>
        <label for='loan_quantity' class='col-sm-3 control-label'>Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			@if ($loan->loan_code=='on_loan')	
					{{ Form::label('loan_quantity', $loan->loan_quantity, ['id'=>'loan_quantity', 'class'=>'form-control']) }}
			@else
					{{ Form::text('loan_quantity', null, ['id'=>'loan_quantity', 'class'=>'form-control','placeholder'=>'',]) }}
			@endif
            @if ($errors->has('loan_quantity')) <p class="help-block">{{ $errors->first('loan_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_description')) has-error @endif'>
        <label for='loan_description' class='col-sm-3 control-label'>Description</label>
        <div class='col-sm-9'>
			{{ Form::label('loan_description', str_replace(chr(13), "\n", $loan->loan_description." "), ['class'=>'form-control']) }}
            @if ($errors->has('loan_description')) <p class="help-block">{{ $errors->first('loan_description') }}</p> @endif
        </div>
    </div>


	@if ($loan->type_code!='folder' && $loan->loan_code != 'exchange' && $loan->loan_code != 'exchanged')
    <div class='form-group  @if ($errors->has('loan_recur')) has-error @endif'>
        {{ Form::label('loan_recur', 'Recurring Daily',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('loan_recur', '1') }}
            @if ($errors->has('loan_recur')) <p class="help-block">{{ $errors->first('loan_recur') }}</p> @endif
        </div>
    </div>
	@endif
	@endif


	<div class='page-header'>
		<h4>
			Response
		</h4>
	</div>

@if ($loan->type_code == 'indent')
    <div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-3 control-label'>Status<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('change_status', $loan_status,$loan->loan_code, ['id'=>'loan_code','class'=>'form-control','onchange'=>'statusChanged()']) }}
            @if ($errors->has('loan_code')) <p class="help-block">{{ $errors->first('loan_code') }}</p> @endif
        </div>
    </div>
@endif

@if ($loan->type_code != 'indent')
	<!--
	@if ($loan->loan_code=='exchange' or $loan->loan_code=='exchanged')
    <div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-3 control-label'>qqqqqStatus<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('loan_code', $loan_status,null, ['id'=>'loan_code','class'=>'form-control','onchange'=>'statusChanged()']) }}
            @if ($errors->has('loan_code')) <p class="help-block">{{ $errors->first('loan_code') }}</p> @endif
        </div>
    </div>
	@endif
	-->
	
	@if (empty($loan->loan_date_start))
			@if ($loan->loan_code!='on_loan')
			<div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
				<label for='loan_code' class='col-sm-3 control-label'>Status</label>
				<div class='col-sm-9'>
					{{ Form::select('change_status', $loan_status,$loan->loan_code, ['id'=>'loan_code','class'=>'form-control','onchange'=>'statusChanged()']) }}
					@if ($errors->has('loan_code')) <p class="help-block">{{ $errors->first('loan_code') }}</p> @endif
				</div>
			</div>
			@endif

			@if ($loan->loan_code=='accept')
			<div class='form-group  @if ($errors->has('loan_date_start')) has-error @endif'>
				{{ Form::label('loan_date_start', 'Date Start',['class'=>'col-sm-3 control-label']) }}
				<div class='col-sm-9'>
					{{ Form::text('loan_date_start', null, ['class'=>'form-control','placeholder'=>'',]) }}
					@if ($errors->has('loan_date_start')) <p class="help-block">{{ $errors->first('loan_date_start') }}</p> @endif
				</div>
			</div>

			<div class='form-group  @if ($errors->has('loan_date_end')) has-error @endif'>
				{{ Form::label('loan_date_end', 'Date End',['class'=>'col-sm-3 control-label']) }}
				<div class='col-sm-9'>
					{{ Form::text('loan_date_end', null, ['class'=>'form-control','placeholder'=>'',]) }}
					@if ($errors->has('loan_date_end')) <p class="help-block">{{ $errors->first('loan_date_end') }}</p> @endif
				</div>
			</div>
			@endif
	@endif

	<!-- On Loan -->
	@if (!empty($loan->loan_date_start))
    <div class='form-group  @if ($errors->has('loan_date_start')) has-error @endif'>
        {{ Form::label('loan_date_start', 'Status',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
        {{ Form::label('loan_date_start', 'On Loan',['class'=>'control-label']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_date_start')) has-error @endif'>
        {{ Form::label('loan_date_start', 'Date Start',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('date_start',  date('d F Y', strtotime($loan->loan_date_start)) , ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_date_end')) has-error @endif'>
        {{ Form::label('loan_date_end', 'Date End',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if ($loan->loan_date_end)
            {{ Form::label('date_start',  date('d F Y', strtotime($loan->loan_date_end)) , ['class'=>'form-control','placeholder'=>'',]) }}
			@else
            {{ Form::label('date_end', '-', ['class'=>'form-control','placeholder'=>'',]) }}
			@endif
        </div>
    </div>
	@endif

	@if (!empty($loan->loan_date_start) && empty($loan->loan_closure_datetime))
	<div class='page-header'>
		<h4>Resolution</h4>
	</div>

	@if (!empty($loan->loan_date_start))
    <div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-3 control-label'>Status<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('change_status', $loan_status,$loan->loan_code, ['id'=>'loan_code','class'=>'form-control','onchange'=>'statusChanged()']) }}
            @if ($errors->has('loan_code')) <p class="help-block">{{ $errors->first('loan_code') }}</p> @endif
        </div>
    </div>
	@endif

    <div class='form-group  @if ($errors->has('loan_closure_description')) has-error @endif'>
        {{ Form::label('loan_closure_description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('loan_closure_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('loan_closure_description')) <p class="help-block">{{ $errors->first('loan_closure_description') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('closure_date')) has-error @endif'>
						{{ Form::label('Resolution Date', 'Resolution Date',['class'=>'col-md-6 control-label']) }}
						
						<div class='col-md-6'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="closure_date" id="closure_date" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($loan->loan_closure_datetime) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('closure_time')) has-error @endif'>
						{{ Form::label('Time', 'Time',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
								<div class="input-group clockpicker" data-autoclose="true">
										{{ Form::text('closure_time', DojoUtility::timeReadFormat($loan->loan_closure_datetime), ['id'=>'closure_time','class'=>'form-control','data-mask'=>'99:99']) }}
										<span class="input-group-addon">
											<span class="fa fa-clock-o"></span>
										</span>
								</div>

						</div>
					</div>
			</div>
	</div>
	@endif

@endif

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@if ($loan->type_code=='folder')
            <a class="btn btn-default" href="/loans?type=folder" role="button">Cancel</a>
			@else
            <a class="btn btn-default" href="/loans" role="button">Cancel</a>
			@endif
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	{{ Form::hidden('ward_code', $loan->ward_code) }}
	{{ Form::hidden('item_code', $loan->item_code) }}
	{{ Form::hidden('loan_request_by', $loan->loan_request_by) }}

	@if ($loan->loan_code=='exchange')
	{{ Form::hidden('loan_code', $loan->loan_code) }}
	@endif
	<script>
		$('#loan_date_start').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('#loan_date_end').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
		$('#closure_date').datepicker({
						format: "dd/mm/yyyy",
						todayBtn: "linked",
						keyboardNavigation: false,
						forceParse: false,
						calendarWeeks: true,
						autoclose: true
		});
		$('.clockpicker').clockpicker();

		/**
		$('#loan_closure_datetime').datepicker({
		format: "dd/mm/yyyy HH:mm",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$(function(){
				$('#loan_date_start').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $loan->loan_date_start }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});

		$(function(){
				$('#loan_date_end').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $loan->loan_date_end }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
		$(function(){
				$('#loan_closure_datetime').combodate({
						format: "DD/MM/YYYY HH:mm",
						template: "DD MMMM YYYY     HH : mm",
						value: '{{ $loan->loan_closure_datetime }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
		**/
	</script>
	<script>
		function set_date_start() {
				document.getElementById('loan_code').value='on_loan';
				$('#loan_date_start').combodate('setValue','{{ $today_date }}');
		}
		function set_date_end() {
				$('#loan_date_end').combodate('setValue','{{ $today_date }}');
		}
		function clear_date_start() {
				$('#loan_date_start').val("");
		}
		function clear_date_end() {
				$('#loan_date_end').combodate('clearValue');
				$('#loan_date_end').combodate('setValue','');
				document.getElementById('loan_date_end').value="";
		}
		function clear_return_date() {
				/**
				$('#loan_closure_datetime').combodate('clearValue');
				$('#loan_closure_datetime').combodate('setValue','');
				**/
				document.getElementById('loan_closure_datetime').value="";
		}
		function set_closure_date() {
				@if ($loan->loan_code<>'exchange')
				document.getElementById('loan_code').value='return';
				@endif
				$('#loan_closure_datetime').combodate('setValue','{{ $today_datetime }}');
		}

		function statusChanged() {
				$loanCode = document.getElementById('loan_code').value;
				if ($loanCode=='on_loan') {
					$('#loan_date_start').val("{{ $today_date }}");
					/**
					$('#loan_date_start').combodate('setValue','{{ $today_date }}');
					**/
				}
				if ($loanCode=='return' || $loanCode=='damage' || $loanCode=='lost') {
					$('#closure_date').val("{{ $today_date }}");
					$('#closure_time').val("{{ $today_time }}");
					/**
					$('#loan_closure_datetime').combodate('setValue','{{ $today_datetime }}');
					**/
				}
				if ($loanCode=='exchanged') {
					$('#loan_closure_datetime').val("{{ $today_datetime }}");
				}
				if ($loanCode=='exchange') {
					clear_return_date();
				}
		}
	</script>
