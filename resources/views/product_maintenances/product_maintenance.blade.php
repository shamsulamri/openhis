
	<div class='form-group  @if ($errors->has('maintain_datetime')) has-error @endif'>
		{{ Form::label('date', 'Date',['class'=>'col-md-3 control-label']) }}
		<div class='col-md-9'>
			<input id="maintain_datetime" name="maintain_datetime" type="text">
			@if ($errors->has('maintain_datetime')) <p class="help-block">{{ $errors->first('maintain_datetime') }}</p> @endif
		</div>
	</div>
    <div class='form-group  @if ($errors->has('reason_code')) has-error @endif'>
        <label for='reason_code' class='col-sm-3 control-label'>Reason<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('reason_code', $reason,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('reason_code')) <p class="help-block">{{ $errors->first('reason_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('maintain_description')) has-error @endif'>
        {{ Form::label('description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('maintain_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('maintain_description')) <p class="help-block">{{ $errors->first('maintain_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/product_maintenances/{{ $product->product_code }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	
	{{ Form::hidden('product_code', $product->product_code) }}
	<script>
		$(function(){
				$('#maintain_datetime').combodate({
						format: "DD/MM/YYYY HH:mm",
						template: "DD MMMM YYYY     HH : mm",
						value: '{{ $product_maintenance->maintain_datetime }}',
						maxYear: 2016,
						minYear: 1900,
						customClass: 'select'
				});    
		});
	</script>
