
    <div class='form-group  @if ($errors->has('sponsor_code')) has-error @endif'>
        {{ Form::label('sponsor_code', 'Sponsor',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('sponsor_code', $sponsor,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('sponsor_code')) <p class="help-block">{{ $errors->first('sponsor_code') }}</p> @endif
        </div>
    </div>

	<!--
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        {{ Form::label('product_code', 'product_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('product_code', $product,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>
	-->
    <div class='form-group  @if ($errors->has('parent_code')) has-error @endif'>
        {{ Form::label('parent_code', 'Parent Category',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('parent_code', $parent,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('parent_code')) <p class="help-block">{{ $errors->first('parent_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('category_code')) has-error @endif'>
        {{ Form::label('category_code', 'Category',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('category_code', $category,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('category_code')) <p class="help-block">{{ $errors->first('category_code') }}</p> @endif
        </div>
    </div>


    <div class='form-group  @if ($errors->has('discount_amount')) has-error @endif'>
        <label for='discount_amount' class='col-sm-2 control-label'>Discount<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('discount_amount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('discount_amount')) <p class="help-block">{{ $errors->first('discount_amount') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/discount_rules" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
