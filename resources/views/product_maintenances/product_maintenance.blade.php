
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('maintain_date')) has-error @endif'>
						{{ Form::label('date', 'Date',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-5'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="maintain_date" id="maintain_date" type="text" class="form-control" value="{{ $product_maintenance->maintain_date }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('maintain_date')) <p class="help-block">{{ $errors->first('maintain_date') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-3">
					<div class='form-group  @if ($errors->has('maintain_time')) has-error @endif'>
						{{ Form::label('Time', 'Time',['class'=>'col-md-2 control-label']) }}
						<div class='col-md-10'>
								<div id="maintain_time" name="maintain_time" class="input-group clockpicker" data-autoclose="true">
										{{ Form::text('maintain_time', null, ['class'=>'form-control','data-mask'=>'99:99']) }}
										<span class="input-group-addon">
											<span class="fa fa-clock-o"></span>
										</span>
								</div>

						</div>
					</div>
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

		$('#maintain_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('.clockpicker').clockpicker();
	</script>
