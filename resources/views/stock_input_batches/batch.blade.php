@extends('layouts.app2')

@section('content')
		<a class="btn btn-default" href="/stock_inputs/input/{{ $input_id }}" role="button">Back</a>
		<a href='#' onclick='submitForm()' class='btn btn-primary'>Save</a>
<br>
<br>
<h3>{{ $product->product_name }}</h3>
<h4>Line Quantity: {{ $line->line_quantity }}</h4>
<form id='form' action='/stock_input_batch/add' method='post'>
		<table class="table table-hover">
			<thead>
			<tr>
				<th width='35%'>Number</th>
				<th width='35%'><div align='center'>Expiry Date<div></th>
				<th width='20%'><div align='center'>Quantity<div></th>
				<th></th>
			</tr>
			</thead>
			<tr class='warning'>
				<td class='@if ($errors->has('batch_number')) has-error @endif'>
						{{ Form::text('batch_number', null,['placeholder'=>'Batch Number', 'class'=>'form-control']) }}
            			@if ($errors->has('batch_number')) <p class="help-block">{{ $errors->first('batch_number') }}</p> @endif
				</td>
				<td class='@if ($errors->has('batch_expiry_date')) has-error @endif'>
					<div class="input-group date">
						{{ Form::text('batch_expiry_date',null, ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'batch_expiry_date']) }}
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
            		@if ($errors->has('batch_expiry_date')) <p class="help-block">{{ $errors->first('batch_expiry_date') }}</p> @endif
				</td>
				<td class='@if ($errors->has('batch_quantity')) has-error @endif'>
						{{ Form::text('batch_quantity', null,['placeholder'=>'Quantity', 'class'=>'form-control']) }}
            			@if ($errors->has('batch_quantity')) <p class="help-block">{{ $errors->first('batch_quantity') }}</p> @endif
				</td>
				<td>
						<a href='#' onclick='submitForm()' class='btn btn-primary'><span class='glyphicon glyphicon-plus'></span></a>
				</td>
			</tr>
		<input type='hidden' name="_token" value="{{ csrf_token() }}">
		<input type='hidden' name="product_code" value="{{ $product_code }}">
		<input type='hidden' name="line_id" value="{{ $line_id}}">
		<input type='hidden' name="input_id" value="{{ $input_id }}">
	@foreach ($batches as $batch)	
			<tr>
				<td>
						{{ Form::label('batch_number', $batch->batch_number, ['class'=>'form-control','placeholder'=>'',]) }}
				</td>
				<td>
						{{ Form::label('batch_expiry_date', DojoUtility::dateReadFormat($batch->batch_expiry_date), ['class'=>'form-control','placeholder'=>'',]) }}
				</td>
				<td>
						{{ Form::text('batch_quantity_'.$batch->batch_id, $batch->batch_quantity, ['class'=>'form-control']) }}
				</td>
				<td>
					<a class='btn btn-danger' href='{{ URL::to('stock_input_batches/delete/'. $batch->batch_id) }}'>
						<span class='glyphicon glyphicon-minus'></span>
					</a>
				</td>
			</tr>
	@endforeach
		</table>
</form>
<script>

		$('#batch_expiry_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		function submitForm() {
			document.getElementById('form').submit();
		}

</script>
@endsection
