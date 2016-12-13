	<h3>Order Report</h3>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-warning" href="/orders" role="button">Back</a>
        </div>
    </div>
	<div class='form-group'>
        {{ Form::label('product', 'Product',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('product_name', $product->product_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

    <div class='form-group'>
        {{ Form::label('product_code', 'Code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('product_code', $product->product_code, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

	@if ($order->order_report)
    <div class='form-group  @if ($errors->has('order_report')) has-error @endif'>
        {{ Form::label('Report', 'Report',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('order_report', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('order_report')) <p class="help-block">{{ $errors->first('order_report') }}</p> @endif
        </div>
    </div>
	@endif

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@if ($diagnostic['contained']) 
				<table class="table table-hover">
				 <thead>
					<tr> 
					<th>Name</th>
					<th>Value</th> 
					<th>Range</th>
					<th></th>
					</tr>
				  </thead>
					<tbody>
					@foreach ($diagnostic['contained'] as $row) 
						<?php
							$diagnostic_name = $row['code']['text'];
							$diagnostic_value = $row['valueQuantity']['value'] . $row['valueQuantity']['unit'];
							$diagnostic_low = null;
							$diagnostic_high = null;
							if (!empty($row['referenceRange'][0])) {
								if (!empty($row['referenceRange'][0]['high'])) {
									$diagnostic_high = $row['referenceRange'][0]['high']['value'];
								}
								if (!empty($row['referenceRange'][0]['low'])) {
									$diagnostic_low = $row['referenceRange'][0]['low']['value'];
								}
							}
							$diagnostic_interpretation = null;
							if (!empty($row['interpretation'])) {
									$diagnostic_interpretation = $row['interpretation']['coding'][0]['code'];
							}

						?>
						<tr>
							<td>{{ $diagnostic_name }}</td>	
							<td>{{ $diagnostic_value }}</td>	
							<td>{{ $diagnostic_low }} - {{ $diagnostic_high }}</td>	
							<td>{{ $diagnostic_interpretation }}</td>	
						</tr>
						
					@endforeach
			@endif
				</table>
			</div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-warning" href="/orders" role="button">Back</a>
        </div>
    </div>
