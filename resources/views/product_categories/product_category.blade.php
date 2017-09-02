
    <div class='form-group  @if ($errors->has('category_name')) has-error @endif'>
        <label for='category_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('category_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('category_name')) <p class="help-block">{{ $errors->first('category_name') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('group_code')) has-error @endif'>
		{{ Form::label('group_code', 'Group',['class'=>'col-sm-3 control-label']) }}
		<div class='col-sm-9'>
			{{ Form::select('group_code', $product_group,null, ['id'=>'group_code', 'class'=>'form-control','maxlength'=>'20']) }}
			@if ($errors->has('group_code')) <p class="help-block">{{ $errors->first('group_code') }}</p> @endif
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
            <a class="btn btn-default" href="/product_categories" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
