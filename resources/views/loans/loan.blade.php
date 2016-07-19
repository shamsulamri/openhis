
    <div class='form-group  @if ($errors->has('item_code')) has-error @endif'>
        <label for='item_code' class='col-sm-2 control-label'>Request Date<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('created_at', $loan->created_at, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('item_code')) <p class="help-block">{{ $errors->first('item_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('item_code')) has-error @endif'>
        <label for='item_code' class='col-sm-2 control-label'>item_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('item_code', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('item_code')) <p class="help-block">{{ $errors->first('item_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_request_by')) has-error @endif'>
        <label for='loan_request_by' class='col-sm-2 control-label'>loan_request_by<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('loan_request_by', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('loan_request_by')) <p class="help-block">{{ $errors->first('loan_request_by') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>ward_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('ward_code', $ward,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_quantity')) has-error @endif'>
        <label for='loan_quantity' class='col-sm-2 control-label'>loan_quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('loan_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('loan_quantity')) <p class="help-block">{{ $errors->first('loan_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_description')) has-error @endif'>
        <label for='loan_description' class='col-sm-2 control-label'>loan_description<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::textarea('loan_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('loan_description')) <p class="help-block">{{ $errors->first('loan_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_recur')) has-error @endif'>
        {{ Form::label('loan_recur', 'loan_recur',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('loan_recur', '1') }}
            @if ($errors->has('loan_recur')) <p class="help-block">{{ $errors->first('loan_recur') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_date_start')) has-error @endif'>
        {{ Form::label('loan_date_start', 'loan_date_start',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('loan_date_start', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('loan_date_start')) <p class="help-block">{{ $errors->first('loan_date_start') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_date_end')) has-error @endif'>
        {{ Form::label('loan_date_end', 'loan_date_end',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('loan_date_end', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('loan_date_end')) <p class="help-block">{{ $errors->first('loan_date_end') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_return')) has-error @endif'>
        {{ Form::label('loan_return', 'loan_return',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('loan_return', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('loan_return')) <p class="help-block">{{ $errors->first('loan_return') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_return_description')) has-error @endif'>
        {{ Form::label('loan_return_description', 'loan_return_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('loan_return_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('loan_return_description')) <p class="help-block">{{ $errors->first('loan_return_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-2 control-label'>loan_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('loan_code', $loan_status,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('loan_code')) <p class="help-block">{{ $errors->first('loan_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/loans" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	<script>
		$(function(){
				$('#loan_date_start').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $loan->loan_date_start }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});

		$(function(){
				$('#loan_date_end').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $loan->loan_date_end }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
	</script>
