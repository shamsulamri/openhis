
    <div class='form-group  @if ($errors->has('move_name')) has-error @endif'>
        <label for='move_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('move_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('move_name')) <p class="help-block">{{ $errors->first('move_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('move_prefix')) has-error @endif'>
        <label for='move_prefix' class='col-sm-3 control-label'>Prefix<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('move_prefix', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('move_prefix')) <p class="help-block">{{ $errors->first('move_prefix') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('tax_code')) has-error @endif'>
		{{ Form::label('gl_code', 'GL Code',['class'=>'col-sm-3 control-label']) }}
		<div class='col-sm-9'>
			{{ Form::select('gl_code', $general_ledger,null, ['id'=>'gl_code', 'class'=>'form-control','maxlength'=>'20']) }}
			@if ($errors->has('gl_code')) <p class="help-block">{{ $errors->first('gl_code') }}</p> @endif
		</div>
	</div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/stock_movements" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
