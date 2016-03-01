
    <div class='form-group  @if ($errors->has('category_name')) has-error @endif'>
        <label for='category_name' class='col-sm-2 control-label'>category_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('category_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('category_name')) <p class="help-block">{{ $errors->first('category_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('category_color')) has-error @endif'>
        {{ Form::label('category_color', 'category_color',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('category_color', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('category_color')) <p class="help-block">{{ $errors->first('category_color') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/product_categories" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
