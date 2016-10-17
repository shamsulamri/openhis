	
	@if ($loan->loan_code=='accept')
			<div class='alert alert-info'>Loan Accepted</div>
	@endif
	<div class='page-header'>
		<h4>Request Information</h4>
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
			Code
		@endif
		</label>
        <div class='col-sm-9'>
            {{ Form::label('item_code', $loan->item_code, ['class'=>'form-control','placeholder'=>'',]) }}
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

	@if (!$loan->loan_is_folder)
    <div class='form-group  @if ($errors->has('loan_quantity')) has-error @endif'>
        <label for='loan_quantity' class='col-sm-3 control-label'>Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('loan_quantity', null, ['id'=>'loan_quantity', 'class'=>'form-control','placeholder'=>'',]) }}
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

	@if (!$loan->loan_is_folder && $loan->loan_code != 'exchange' && $loan->loan_code != 'exchanged')
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
			Loan Request
		</h4>
	</div>

	@if ($loan->loan_code=='exchange' or $loan->loan_code=='exchanged')
    <div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-3 control-label'>Status<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('loan_code', $loan_status,null, ['id'=>'loan_code','class'=>'form-control','onchange'=>'statusChanged()']) }}
            @if ($errors->has('loan_code')) <p class="help-block">{{ $errors->first('loan_code') }}</p> @endif
        </div>
    </div>
	@endif
	
	@if ($loan->loan_code!='lend')
    <div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-3 control-label'>Status<span style='color:red;'> *</span></label>
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
			<a href='javascript:set_date_start()' class='btn btn-default btn-xs'>Today</a>
			<a href='javascript:clear_date_start()' class='btn btn-default btn-xs'>Clear</a>
            @if ($errors->has('loan_date_start')) <p class="help-block">{{ $errors->first('loan_date_start') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_date_end')) has-error @endif'>
        {{ Form::label('loan_date_end', 'Date End',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('loan_date_end', null, ['class'=>'form-control','placeholder'=>'',]) }}
			<a href='javascript:set_date_end()' class='btn btn-default btn-xs'>Today</a>
			<a href='javascript:clear_date_end()' class='btn btn-default btn-xs'>Clear</a>
            @if ($errors->has('loan_date_end')) <p class="help-block">{{ $errors->first('loan_date_end') }}</p> @endif
        </div>
    </div>
	@endif

	@if ($loan->loan_code=='lend')
    <div class='form-group  @if ($errors->has('loan_date_start')) has-error @endif'>
        {{ Form::label('loan_date_start', 'Date Start',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('date_start',  date('d F Y, H:i', strtotime($loan->getLoanDateStart())) , ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_date_end')) has-error @endif'>
        {{ Form::label('loan_date_end', 'Date End',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if ($loan->loan_date_end)
            {{ Form::label('date_start',  date('d F Y, H:i', strtotime($loan->getLoanDateEnd())) , ['class'=>'form-control','placeholder'=>'',]) }}
			@else
            {{ Form::label('date_end', '-', ['class'=>'form-control','placeholder'=>'',]) }}
			@endif
        </div>
    </div>
	@endif

	@if ($loan->loan_code=='lend' || $loan->loan_code=='exchange')
	<div class='page-header'>
		<h4>{{ $information }}</h4>
	</div>

	@if ($loan->loan_code=='lend')
    <div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-3 control-label'>Status<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('change_status', $loan_status,null, ['id'=>'loan_code','class'=>'form-control','onchange'=>'statusChanged()']) }}
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

    <div class='form-group  @if ($errors->has('loan_closure_datetime')) has-error @endif'>
        {{ Form::label('loan_closure_datetime', 'Date',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('loan_closure_datetime', null, ['class'=>'form-control','placeholder'=>'',]) }}
			<a href='javascript:set_closure_date()' class='btn btn-default btn-xs'>Today</a>
			<a href='javascript:clear_return_date()' class='btn btn-default btn-xs'>Clear</a>
            @if ($errors->has('loan_closure_datetime')) <p class="help-block">{{ $errors->first('loan_closure_datetime') }}</p> @endif
        </div>
    </div>
	@endif

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@if ($loan->loan_is_folder)
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
	</script>
	<script>
		function set_date_start() {
				document.getElementById('loan_code').value='lend';
				$('#loan_date_start').combodate('setValue','{{ $today }}');
		}
		function set_date_end() {
				$('#loan_date_end').combodate('setValue','{{ $today }}');
		}
		function clear_date_start() {
				$('#loan_date_start').combodate('clearValue');
				$('#loan_date_start').combodate('setValue','');
				document.getElementById('loan_date_start').value="";
		}
		function clear_date_end() {
				$('#loan_date_end').combodate('clearValue');
				$('#loan_date_end').combodate('setValue','');
				document.getElementById('loan_date_end').value="";
		}
		function clear_return_date() {
				$('#loan_closure_datetime').combodate('clearValue');
				$('#loan_closure_datetime').combodate('setValue','');
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
				if ($loanCode=='lend') {
					$('#loan_date_start').combodate('setValue','{{ $today }}');
				}
				if ($loanCode=='return' || $loanCode=='damage' || $loanCode=='lost') {
					$('#loan_closure_datetime').combodate('setValue','{{ $today_datetime }}');
				}
				if ($loanCode=='exchanged') {
					$('#loan_closure_datetime').combodate('setValue','{{ $today_datetime }}');
				}
				if ($loanCode=='exchange') {
					clear_return_date();
				}
		}
	</script>

