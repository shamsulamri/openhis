
    <div class='form-group  @if ($errors->has('category_code')) has-error @endif'>
        <label for='category_code' class='col-sm-2 control-label'>Redirect<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('category_code', $category,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('category_code')) <p class="help-block">{{ $errors->first('category_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('encounter_code')) has-error @endif'>
        <label for='encounter_code' class='col-sm-2 control-label'>From<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('encounter_code', $encounter,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('encounter_code')) <p class="help-block">{{ $errors->first('encounter_code') }}</p> @endif
        </div>
    </div>


    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
        <label for='location_code' class='col-sm-2 control-label'>To<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('location_code', $location,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/order_routes" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
