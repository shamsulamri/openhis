
    <div class='form-group  @if ($errors->has('supplier_name')) has-error @endif'>
        <label for='supplier_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('supplier_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('supplier_name')) <p class="help-block">{{ $errors->first('supplier_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_company_number')) has-error @endif'>
        <label for='supplier_company_number' class='col-sm-3 control-label'>Company Number<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('supplier_company_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('supplier_company_number')) <p class="help-block">{{ $errors->first('supplier_company_number') }}</p> @endif
        </div>
    </div>

	<div class='page-header'>
		<h4>Address</h4>
	</div>
    <div class='form-group  @if ($errors->has('supplier_street_1')) has-error @endif'>
        {{ Form::label('supplier_street_1', 'Street 1',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('supplier_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('supplier_street_1')) <p class="help-block">{{ $errors->first('supplier_street_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_street_2')) has-error @endif'>
        {{ Form::label('supplier_street_2', 'Street 2',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('supplier_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('supplier_street_2')) <p class="help-block">{{ $errors->first('supplier_street_2') }}</p> @endif
        </div>
    </div>




	<div class="row">
			<div class="col-xs-6">
				<div class='form-group  @if ($errors->has('supplier_city')) has-error @endif'>
						{{ Form::label('City', 'City',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::text('supplier_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
							@if ($errors->has('supplier_city')) <p class="help-block">{{ $errors->first('supplier_city') }}</p> @endif
						</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class='form-group  @if ($errors->has('supplier_postcode')) has-error @endif'>
						{{ Form::label('Postcode', 'Postcode',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::text('supplier_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'5']) }}
							@if ($errors->has('supplier_postcode')) <p class="help-block">{{ $errors->first('supplier_postcode') }}</p> @endif
						</div>
				</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('supplier_state')) has-error @endif'>
						{{ Form::label('State', 'State',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::select('supplier_state', $state, null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('supplier_state')) <p class="help-block">{{ $errors->first('supplier_state') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('supplier_country')) has-error @endif'>
						{{ Form::label('Country', 'Country',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::select('supplier_country', $nation,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('supplier_country')) <p class="help-block">{{ $errors->first('supplier_country') }}</p> @endif
						</div>
					</div>
			</div>
	</div>





	<!--
    <div class='form-group  @if ($errors->has('supplier_city')) has-error @endif'>
        {{ Form::label('supplier_city', 'City',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('supplier_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('supplier_city')) <p class="help-block">{{ $errors->first('supplier_city') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_postcode')) has-error @endif'>
        {{ Form::label('supplier_postcode', 'Postcode',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('supplier_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('supplier_postcode')) <p class="help-block">{{ $errors->first('supplier_postcode') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_state')) has-error @endif'>
        {{ Form::label('supplier_state', 'State',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('supplier_state', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('supplier_state')) <p class="help-block">{{ $errors->first('supplier_state') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_country')) has-error @endif'>
        {{ Form::label('supplier_country', 'Country',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('supplier_country', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('supplier_country')) <p class="help-block">{{ $errors->first('supplier_country') }}</p> @endif
        </div>
    </div>
	-->

	<div class='page-header'>
		<h4>Contact Information</h4>
	</div>
    <div class='form-group  @if ($errors->has('supplier_phone')) has-error @endif'>
        {{ Form::label('supplier_phone', 'Phone',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('supplier_phone', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('supplier_phone')) <p class="help-block">{{ $errors->first('supplier_phone') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_person')) has-error @endif'>
        {{ Form::label('supplier_person', 'Contact Person',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('supplier_person', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('supplier_person')) <p class="help-block">{{ $errors->first('supplier_person') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_account')) has-error @endif'>
        {{ Form::label('supplier_account', 'Supplier Account',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('supplier_account', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('supplier_account')) <p class="help-block">{{ $errors->first('supplier_account') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/suppliers" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
