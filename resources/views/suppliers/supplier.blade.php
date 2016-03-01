
    <div class='form-group  @if ($errors->has('supplier_name')) has-error @endif'>
        <label for='supplier_name' class='col-sm-2 control-label'>supplier_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('supplier_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('supplier_name')) <p class="help-block">{{ $errors->first('supplier_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_street_1')) has-error @endif'>
        {{ Form::label('supplier_street_1', 'supplier_street_1',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('supplier_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('supplier_street_1')) <p class="help-block">{{ $errors->first('supplier_street_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_street_2')) has-error @endif'>
        {{ Form::label('supplier_street_2', 'supplier_street_2',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('supplier_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('supplier_street_2')) <p class="help-block">{{ $errors->first('supplier_street_2') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_city')) has-error @endif'>
        {{ Form::label('supplier_city', 'supplier_city',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('supplier_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('supplier_city')) <p class="help-block">{{ $errors->first('supplier_city') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_postcode')) has-error @endif'>
        {{ Form::label('supplier_postcode', 'supplier_postcode',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('supplier_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'10']) }}
            @if ($errors->has('supplier_postcode')) <p class="help-block">{{ $errors->first('supplier_postcode') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_state')) has-error @endif'>
        {{ Form::label('supplier_state', 'supplier_state',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('supplier_state', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('supplier_state')) <p class="help-block">{{ $errors->first('supplier_state') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_country')) has-error @endif'>
        {{ Form::label('supplier_country', 'supplier_country',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('supplier_country', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('supplier_country')) <p class="help-block">{{ $errors->first('supplier_country') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_phone')) has-error @endif'>
        {{ Form::label('supplier_phone', 'supplier_phone',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('supplier_phone', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('supplier_phone')) <p class="help-block">{{ $errors->first('supplier_phone') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_person')) has-error @endif'>
        {{ Form::label('supplier_person', 'supplier_person',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('supplier_person', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('supplier_person')) <p class="help-block">{{ $errors->first('supplier_person') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_account')) has-error @endif'>
        {{ Form::label('supplier_account', 'supplier_account',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('supplier_account', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('supplier_account')) <p class="help-block">{{ $errors->first('supplier_account') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/suppliers" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
