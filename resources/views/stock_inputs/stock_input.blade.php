
    <div class='form-group  @if ($errors->has('move_code')) has-error @endif'>
        <label for='move_code' class='col-sm-2 control-label'>Movement Type<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('move_code', $move,null, ['id'=>'move_code', 'onchange'=>'checkMovementType()' ,'class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('move_code')) <p class="help-block">{{ $errors->first('move_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        <label for='store_code' class='col-sm-2 control-label'>Source Store<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('store_code', $store,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code_transfer')) has-error @endif'>
        <label for='store_code_transfer' class='col-sm-2 control-label'>Target Store</label>
        <div class='col-sm-10'>
            {{ Form::select('store_code_transfer', $store,null, ['id'=>'store_code_transfer', 'class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code_transfer')) <p class="help-block">{{ $errors->first('store_code_transfer') }}</p> @endif
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/stock_inputs" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<script>
		document.getElementById('store_code_transfer').disabled = true;
		checkMovementType();

		function checkMovementType() {
			moveType = document.getElementById('move_code');
			store = document.getElementById('store_code_transfer');
			if (moveType.value=='transfer') {
				store.disabled = false;
			} else {
				store.value = "";
				store.disabled = true;
			}
		}
	</script>
