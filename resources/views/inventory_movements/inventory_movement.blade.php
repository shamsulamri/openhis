
    <div class='form-group  @if ($errors->has('purchase_id')) has-error @endif'>
        {{ Form::label('purchase_id', 'purchase_id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('purchase_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('purchase_id')) <p class="help-block">{{ $errors->first('purchase_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('move_code')) has-error @endif'>
        <label for='move_code' class='col-sm-2 control-label'>move_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('move_code', $move,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('move_code')) <p class="help-block">{{ $errors->first('move_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        <label for='store_code' class='col-sm-2 control-label'>store_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('store_code', $store,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code_transfer')) has-error @endif'>
        {{ Form::label('store_code_transfer', 'store_code_transfer',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('store_code_transfer', $store_transfer,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code_transfer')) <p class="help-block">{{ $errors->first('store_code_transfer') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('move_description')) has-error @endif'>
        {{ Form::label('move_description', 'move_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('move_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('move_description')) <p class="help-block">{{ $errors->first('move_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('move_posted')) has-error @endif'>
        <label for='move_posted' class='col-sm-2 control-label'>move_posted<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::checkbox('move_posted', '1') }}
            @if ($errors->has('move_posted')) <p class="help-block">{{ $errors->first('move_posted') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/inventory_movements" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
