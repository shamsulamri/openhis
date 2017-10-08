
    <div class='form-group  @if ($errors->has('discount_amount')) has-error @endif'>
        <label for='discount_amount' class='col-sm-2 control-label'>Amount<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('discount_amount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('discount_amount')) <p class="help-block">{{ $errors->first('discount_amount') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/bill_items/{{ $bill_discount->encounter_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('encounter_id', $bill_discount->encounter_id) }} 
