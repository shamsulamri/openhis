
    <div class='form-group  @if ($errors->has('supplier_code')) has-error @endif'>
        <label for='supplier_code' class='col-sm-2 control-label'>supplier_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('supplier_code', $supplier,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('supplier_code')) <p class="help-block">{{ $errors->first('supplier_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_date')) has-error @endif'>
        <label for='purchase_date' class='col-sm-2 control-label'>purchase_date<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('purchase_date', null, ['class'=>'form-control','placeholder'=>'dd/mm/yyyy',]) }}
            @if ($errors->has('purchase_date')) <p class="help-block">{{ $errors->first('purchase_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_reference')) has-error @endif'>
        {{ Form::label('purchase_reference', 'purchase_reference',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('purchase_reference', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('purchase_reference')) <p class="help-block">{{ $errors->first('purchase_reference') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_posted')) has-error @endif'>
        {{ Form::label('purchase_posted', 'purchase_posted',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('purchase_posted', '1') }}
            @if ($errors->has('purchase_posted')) <p class="help-block">{{ $errors->first('purchase_posted') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_received')) has-error @endif'>
        {{ Form::label('purchase_received', 'purchase_received',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('purchase_received', '1') }}
            @if ($errors->has('purchase_received')) <p class="help-block">{{ $errors->first('purchase_received') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        {{ Form::label('store_code', 'store_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('store_code', $store,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_description')) has-error @endif'>
        {{ Form::label('purchase_description', 'purchase_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('purchase_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('purchase_description')) <p class="help-block">{{ $errors->first('purchase_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('receive_datetime')) has-error @endif'>
        {{ Form::label('receive_datetime', 'receive_datetime',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('receive_datetime', null, ['class'=>'form-control','placeholder'=>'dd/mm/yyyy',]) }}
            @if ($errors->has('receive_datetime')) <p class="help-block">{{ $errors->first('receive_datetime') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_number')) has-error @endif'>
        {{ Form::label('purchase_number', 'purchase_number',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('purchase_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('purchase_number')) <p class="help-block">{{ $errors->first('purchase_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('invoice_number')) has-error @endif'>
        {{ Form::label('invoice_number', 'invoice_number',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('invoice_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('invoice_number')) <p class="help-block">{{ $errors->first('invoice_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('invoice_date')) has-error @endif'>
        {{ Form::label('invoice_date', 'invoice_date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('invoice_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('invoice_date')) <p class="help-block">{{ $errors->first('invoice_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/purchase_orders" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
