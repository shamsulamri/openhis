
    <div class='form-group  @if ($errors->has('sponsor_name')) has-error @endif'>
        <label for='sponsor_name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('sponsor_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('sponsor_name')) <p class="help-block">{{ $errors->first('sponsor_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_street_1')) has-error @endif'>
        {{ Form::label('sponsor_street_1', 'Street 1',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sponsor_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('sponsor_street_1')) <p class="help-block">{{ $errors->first('sponsor_street_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sponsor_street_2')) has-error @endif'>
        {{ Form::label('sponsor_street_2', 'Street 2',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sponsor_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('sponsor_street_2')) <p class="help-block">{{ $errors->first('sponsor_street_2') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('sponsor_city')) has-error @endif'>
						{{ Form::label('sponsor_city', 'City',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('sponsor_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
							@if ($errors->has('sponsor_city')) <p class="help-block">{{ $errors->first('sponsor_city') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('sponsor_postcode')) has-error @endif'>
						{{ Form::label('sponsor_postcode', 'Postcode',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('sponsor_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
							@if ($errors->has('sponsor_postcode')) <p class="help-block">{{ $errors->first('sponsor_postcode') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('sponsor_state')) has-error @endif'>
						{{ Form::label('sponsor_state', 'State',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('sponsor_state', $state, null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('sponsor_state')) <p class="help-block">{{ $errors->first('sponsor_state') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('sponsor_country')) has-error @endif'>
						{{ Form::label('sponsor_country', 'Country',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('sponsor_country', $nation,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('sponsor_country')) <p class="help-block">{{ $errors->first('sponsor_country') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


    <div class='form-group  @if ($errors->has('sponsor_phone')) has-error @endif'>
        {{ Form::label('sponsor_phone', 'Phone',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sponsor_phone', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('sponsor_phone')) <p class="help-block">{{ $errors->first('sponsor_phone') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/sponsors" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
