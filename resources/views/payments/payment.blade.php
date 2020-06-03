	<br>
	@if ($encounter_id>0)
	<!--
    <div class='form-group  @if ($errors->has('encounter_id')) has-error @endif'>
        <label class='col-sm-2 control-label'>Encounter ID</label>
        <div class='col-sm-10'>
            {{ Form::label('encounter', $encounter_id, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
	-->
	@else
	<!--
    <div class='form-group  @if ($errors->has('encounter_id')) has-error @endif'>
        <label class='col-sm-2 control-label'>Encounter ID</label>
        <div class='col-sm-10'>
            {{ Form::select('encounter_id', $discharges, null, ['class'=>'form-control']) }}
        </div>
    </div>
	-->
    <div class='form-group  @if ($errors->has('bill_id')) has-error @endif'>
        <label class='col-sm-2 control-label'>Bill ID</label>
        <div class='col-sm-10'>
            {{ Form::select('bill_id', $bills, null, ['class'=>'form-control']) }}
        </div>
    </div>
	@endif

    <div class='form-group  @if ($errors->has('payment_outstanding')) has-error @endif'>
        <label class='col-sm-2 control-label'>Balance</label>
        <div class='col-sm-10'>
	<?php 
		$balance = $billHelper->paymentOutstanding($patient->patient_id, $encounter_id, $non_claimable); 
	?>
	@if ($balance>0)
			{{ Form::label('pay', number_format($balance,2), ['class'=>'form-control']) }}
	@else
			{{ Form::label('pay', '-', ['class'=>'form-control']) }}
	@endif
        </div>
    </div>

	<?php 
		if ($is_edit) {
				$balance = $payment->payment_amount;
		}
	?>

    <div class='form-group  @if ($errors->has('payment_amount')) has-error @endif'>
        <label for='payment_amount' class='col-sm-2 control-label'>Amount/Balance<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('payment_amount', $balance, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('payment_amount')) <p class="help-block">{{ $errors->first('payment_amount') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('payment_code')) has-error @endif'>
						<label for='payment_code' class='col-sm-4 control-label'>Type<span style='color:red;'> *</span></label>
						<div class='col-sm-8'>
							{{ Form::select('payment_code', $payment_methods,null, ['id'=>'payment_code','onchange'=>'paymentChanged()','class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('payment_code')) <p class="help-block">{{ $errors->first('payment_code') }}</p> @endif
						</div>
					</div>
			</div>
			<!--
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('sponsor_code')) has-error @endif'>
						<label for='sponsor_code' class='col-sm-4 control-label'>Sponsor</label>
						<div class='col-sm-8'>
							{{ Form::select('sponsor_code', $sponsor,null, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('sponsor_code')) <p class="help-block">{{ $errors->first('sponsor_code') }}</p> @endif
						</div>
					</div>
			</div>
			-->
	</div>


    <div class='form-group  @if ($errors->has('payment_description')) has-error @endif'>
        {{ Form::label('payment_description', 'Note',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('payment_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('payment_description')) <p class="help-block">{{ $errors->first('payment_description') }}</p> @endif
        </div>
    </div>

<!-- Card Information -->
<div class="target">
	<br>
	<h3>Card Information</h3>
	<hr>
	<div class="row">
			<div class="col-xs-6">
			</div>
			<div class="col-xs-6">
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('card_code')) has-error @endif'>
						<label for='card_code' class='col-sm-4 control-label'>Card</label>
						<div class='col-sm-8'>
							{{ Form::select('card_code', $card,$credit->card_code, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('card_code')) <p class="help-block">{{ $errors->first('card_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('credit_number')) has-error @endif'>
						<label for='credit_number' class='col-sm-4 control-label'>Card Number</label>
						<div class='col-sm-8'>
							{{ Form::text('credit_number', $credit->credit_number, ['class'=>'form-control','placeholder'=>'','maxlength'=>'21']) }}
							@if ($errors->has('credit_number')) <p class="help-block">{{ $errors->first('credit_number') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group  @if ($errors->has('credit_expiry_month')) has-error @endif'>
						<label for='credit_expiry_month' class='col-sm-6 control-label'>Expiration Date</label>
						<div class='col-sm-6'>
							{{ Form::select('credit_expiry_month', $expiry_months, $credit->credit_expiry_month, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('credit_expiry_month')) <p class="help-block">{{ $errors->first('credit_expiry_month') }}</p> @endif
							<small>Month</small>
						</div>
					</div>
			</div>
			<div class="col-xs-2">
					<div class='form-group  @if ($errors->has('credit_expiry_year')) has-error @endif'>
						<div class='col-sm-12'>
							{{ Form::select('credit_expiry_year', $expiry_years, $credit->credit_expiry_year, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('credit_expiry_year')) <p class="help-block">{{ $errors->first('credit_expiry_year') }}</p> @endif
							<small>Year</small>
						</div>
					</div>
			</div>
	</div>
	<br>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('credit_first_name')) has-error @endif'>
						<label for='credit_first_name' class='col-sm-4 control-label'>First Name</label>
						<div class='col-sm-8'>
							{{ Form::text('credit_first_name', $credit->credit_first_name, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
							@if ($errors->has('credit_first_name')) <p class="help-block">{{ $errors->first('credit_first_name') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('credit_last_name')) has-error @endif'>
						<label for='credit_last_name' class='col-sm-4 control-label'>Last Name</label>
						<div class='col-sm-8'>
							{{ Form::text('credit_last_name', $credit->credit_last_name, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
							@if ($errors->has('credit_last_name')) <p class="help-block">{{ $errors->first('credit_last_name') }}</p> @endif
						</div>
					</div>
			</div>
	</div>





    <div class='form-group  @if ($errors->has('credit_address_1')) has-error @endif'>
        <label for='credit_address_1' class='col-sm-2 control-label'>Address</label>
        <div class='col-sm-10'>
            {{ Form::text('credit_address_1', $credit->credit_address_1, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('credit_address_1')) <p class="help-block">{{ $errors->first('credit_address_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('credit_address_2')) has-error @endif'>
        <label for='credit_address_2' class='col-sm-2 control-label'></label>
        <div class='col-sm-10'>
            {{ Form::text('credit_address_2', $credit->credit_address_2, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('credit_address_2')) <p class="help-block">{{ $errors->first('credit_address_2') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('credit_city')) has-error @endif'>
						<label for='credit_city' class='col-sm-4 control-label'>City</label>
						<div class='col-sm-8'>
							{{ Form::text('credit_city', $credit->credit_city, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
							@if ($errors->has('credit_city')) <p class="help-block">{{ $errors->first('credit_city') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('credit_postcode')) has-error @endif'>
						<label for='credit_postcode' class='col-sm-4 control-label'>Postcode</label>
						<div class='col-sm-8'>
							{{ Form::text('credit_postcode', $credit->credit_postcode, ['class'=>'form-control','placeholder'=>'','maxlength'=>'16']) }}
							@if ($errors->has('credit_postcode')) <p class="help-block">{{ $errors->first('credit_postcode') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('nation_code')) has-error @endif'>
						<label for='nation_code' class='col-sm-4 control-label'>Country</label>
						<div class='col-sm-8'>
							{{ Form::select('nation_code', $nation,$credit->nation_cde, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('nation_code')) <p class="help-block">{{ $errors->first('nation_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
			</div>
	</div>

    <div class='form-group  @if ($errors->has('credit_phone_number')) has-error @endif'>
        <label for='credit_phone_number' class='col-sm-2 control-label'>Phone Number</label>
        <div class='col-sm-4'>
            {{ Form::text('credit_phone_number', $credit->credit_phone_number, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('credit_phone_number')) <p class="help-block">{{ $errors->first('credit_phone_number') }}</p> @endif
        </div>
    </div>
</div>

<!-- Payor Information -->
<div class="payor">
	<br>
	<h3>Payor Information</h3>
	<hr>
	<h4>Fill in the details below if the payor is not the patient.</h4>
	<br>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('payor_first_name')) has-error @endif'>
						<label for='payor_first_name' class='col-sm-4 control-label'>First Name</label>
						<div class='col-sm-8'>
							{{ Form::text('payor_first_name', $payor->payor_first_name, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
							@if ($errors->has('payor_first_name')) <p class="help-block">{{ $errors->first('payor_first_name') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('payor_last_name')) has-error @endif'>
						<label for='payor_last_name' class='col-sm-4 control-label'>Last Name</label>
						<div class='col-sm-8'>
							{{ Form::text('payor_last_name', $payor->payor_last_name, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
							@if ($errors->has('payor_last_name')) <p class="help-block">{{ $errors->first('payor_last_name') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

    <div class='form-group  @if ($errors->has('payor_address_1')) has-error @endif'>
        <label for='payor_address_1' class='col-sm-2 control-label'>Address</label>
        <div class='col-sm-10'>
            {{ Form::text('payor_address_1', $payor->payor_address_1, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('payor_address_1')) <p class="help-block">{{ $errors->first('payor_address_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('payor_address_2')) has-error @endif'>
        <label for='payor_address_2' class='col-sm-2 control-label'></label>
        <div class='col-sm-10'>
            {{ Form::text('payor_address_2', $payor->payor_address_2, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('payor_address_2')) <p class="help-block">{{ $errors->first('payor_address_2') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('payor_city')) has-error @endif'>
						<label for='payor_city' class='col-sm-4 control-label'>City</label>
						<div class='col-sm-8'>
							{{ Form::text('payor_city', $payor->payor_city, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
							@if ($errors->has('payor_city')) <p class="help-block">{{ $errors->first('payor_city') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('payor_postcode')) has-error @endif'>
						<label for='payor_postcode' class='col-sm-4 control-label'>Postcode</label>
						<div class='col-sm-8'>
							{{ Form::text('payor_postcode', $payor->payor_postcode, ['class'=>'form-control','placeholder'=>'','maxlength'=>'16']) }}
							@if ($errors->has('payor_postcode')) <p class="help-block">{{ $errors->first('payor_postcode') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('state_code')) has-error @endif'>
						<label for='state_code' class='col-sm-4 control-label'>State</label>
						<div class='col-sm-8'>
							{{ Form::select('state_code', $state,$payor->state_code, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('state_code')) <p class="help-block">{{ $errors->first('state_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('nation_code')) has-error @endif'>
						<label for='nation_code' class='col-sm-4 control-label'>Country</label>
						<div class='col-sm-8'>
							{{ Form::select('nation_code', $nation,$payor->nation_code, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('nation_code')) <p class="help-block">{{ $errors->first('nation_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

    <div class='form-group  @if ($errors->has('payor_phone_number')) has-error @endif'>
        <label for='payor_phone_number' class='col-sm-2 control-label'>Phone Number</label>
        <div class='col-sm-4'>
            {{ Form::text('payor_phone_number', $payor->payor_phone_number, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('payor_phone_number')) <p class="help-block">{{ $errors->first('payor_phone_number') }}</p> @endif
        </div>
    </div>
</div>


<!-- Save Cancel -->
    <div class='form-group'>
        <div class="col-sm-12">
			<div class='pull-right'>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
			@if ($payment->encounter_id>0)
            <a class="btn btn-default" href="/bill_items/{{ $payment->encounter_id }}" role="button">Cancel</a>
			@endif
			</div>
        </div>
    </div>

	@if ($payment->encounter_id>0)
		{{ Form::hidden('encounter_id', null) }}
	@endif
    {{ Form::hidden('patient_id', null) }}
    {{ Form::hidden('payment_non_claimable', $non_claimable) }}


<script>
	function paymentChanged() {
			paymentCode = document.getElementById('payment_code').value;
			if (paymentCode=='credit_card') {
					show(document.querySelectorAll('.target'));
					hide(document.querySelectorAll('.payor'));
			} else if (paymentCode=='cash') {
					show(document.querySelectorAll('.payor'));
					hide(document.querySelectorAll('.target'));
			} else {
					hide(document.querySelectorAll('.target'));
					hide(document.querySelectorAll('.payor'));
			}
	}	

	function hide (elements) {
		elements = elements.length ? elements : [elements];
		for (var index = 0; index < elements.length; index++) {
				elements[index].style.display = 'none';
		}
	}

	function show (elements, specifiedDisplay) {
		var computedDisplay, element, index;

		elements = elements.length ? elements : [elements];
		for (index = 0; index < elements.length; index++) {
				element = elements[index];

				element.style.display = '';
				computedDisplay = window.getComputedStyle(element, null).getPropertyValue('display');

				if (computedDisplay === 'none') {
						element.style.display = specifiedDisplay || 'block';
				}
		}
	}

	paymentChanged();
</script>
