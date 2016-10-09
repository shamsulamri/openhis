	<!--
    <div class='form-group  @if ($errors->has('system_administrator')) has-error @endif'>
        <label for='system_administrator' class='col-sm-4 control-label'>System Administrator</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('system_administrator', '1') }}
            @if ($errors->has('system_administrator')) <p class="help-block">{{ $errors->first('system_administrator') }}</p> @endif
        </div>
    </div>
	-->

	<div class='page-header'>
		<h4>Module Access</h4>
	</div>
    <div class='form-group  @if ($errors->has('author_name')) has-error @endif'>
        <label for='author_name' class='col-sm-4 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-8'>
            {{ Form::text('author_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('author_name')) <p class="help-block">{{ $errors->first('author_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('module_patient')) has-error @endif'>
        <label for='module_patient' class='col-sm-4 control-label'>Patient Module</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('module_patient', '1') }}
            @if ($errors->has('module_patient')) <p class="help-block">{{ $errors->first('module_patient') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('module_consultation')) has-error @endif'>
        <label for='module_consultation' class='col-sm-4 control-label'>Consultation Module</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('module_consultation', '1') }}
            @if ($errors->has('module_consultation')) <p class="help-block">{{ $errors->first('module_consultation') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('appointment_function')) has-error @endif'>
        <label for='appointment_function' class='col-sm-4 control-label'>Appointment Module</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('appointment_function', '1') }}
            @if ($errors->has('appointment_function')) <p class="help-block">{{ $errors->first('appointment_function') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('module_inventory')) has-error @endif'>
        <label for='module_inventory' class='col-sm-4 control-label'>Inventory Module</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('module_inventory', '1') }}
            @if ($errors->has('module_inventory')) <p class="help-block">{{ $errors->first('module_inventory') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('module_ward')) has-error @endif'>
        <label for='module_ward' class='col-sm-4 control-label'>Ward Module</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('module_ward', '1') }}
            @if ($errors->has('module_ward')) <p class="help-block">{{ $errors->first('module_ward') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('module_discharge')) has-error @endif'>
        <label for='module_discharge' class='col-sm-4 control-label'>Financial Module</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('module_discharge', '1') }}
            @if ($errors->has('module_discharge')) <p class="help-block">{{ $errors->first('module_discharge') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('module_diet')) has-error @endif'>
        <label for='module_diet' class='col-sm-4 control-label'>Kitchen & Dietary Module</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('module_diet', '1') }}
            @if ($errors->has('module_diet')) <p class="help-block">{{ $errors->first('module_diet') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('module_medical_record')) has-error @endif'>
        <label for='module_medical_record' class='col-sm-4 control-label'>Medical Record Module</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('module_medical_record', '1') }}
            @if ($errors->has('module_medical_record')) <p class="help-block">{{ $errors->first('module_medical_record') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('module_support')) has-error @endif'>
        <label for='module_support' class='col-sm-4 control-label'>Supporting Module</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('module_support', '1') }}
            @if ($errors->has('module_support')) <p class="help-block">{{ $errors->first('module_support') }}</p> @endif
        </div>
    </div>

	
	<div class='page-header'>
		<h4>Functional Access</h4>
	</div>

    <div class='form-group  @if ($errors->has('patient_list')) has-error @endif'>
        <label for='patient_list' class='col-sm-4 control-label'>Patient List</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('patient_list', '1') }}
            @if ($errors->has('patient_list')) <p class="help-block">{{ $errors->first('patient_list') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_list')) has-error @endif'>
        <label for='product_list' class='col-sm-4 control-label'>Product List</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('product_list', '1') }}
            @if ($errors->has('product_list')) <p class="help-block">{{ $errors->first('product_list') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_function')) has-error @endif'>
        <label for='loan_function' class='col-sm-4 control-label'>Loan Function</label>
        <div class='col-sm-8'>
            {{ Form::checkbox('loan_function', '1') }}
            @if ($errors->has('loan_function')) <p class="help-block">{{ $errors->first('loan_function') }}</p> @endif
        </div>
    </div>

	<br>

    <div class='form-group'>
        <div class="col-sm-offset-4 col-sm-8">
            <a class="btn btn-default" href="/user_authorizations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
