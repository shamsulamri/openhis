@extends('layouts.app')

@section('content')

@include('patients.id_only')
<h2>
Edit Future Order
</h2>
<br>
<form action='/order_investigations/edit_date' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-12">
					<div class='form-group' >
						<label for='race_name' class='col-sm-3 control-label'>Product</label>
						<div class='col-sm-9'>
							{{ Form::label('product_name', $product[0]->product_name, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
						</div>
					</div>

					<div class='form-group  @if ($errors->has('investigation_date')) has-error @endif'>
						<label for='investigation_date' class='col-sm-3 control-label'>Date<span style='color:red;'> *</span></label>
						<div class='col-sm-9'>
							{{ $order->investigation_date }}
							<div class="input-group date">
								<input data-mask="99/99/9999" name="investigation_date" id="investigation_date" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($order_investigation->investigation_date) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							<!--
							<input id="investigation_date" name="investigation_date" type="text">
							-->
							@if ($errors->has('investigation_date')) <p class="help-block">{{ $errors->first('investigation_date') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/futures" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<input type='hidden' name="_token" value="{{ csrf_token() }}">
    {{ Form::hidden('id', $order_investigation->id, ['class'=>'form-control input-sm','placeholder'=>'',]) }}
    {{ Form::hidden('product_code', $product[0]->product_code, ['class'=>'form-control input-sm','placeholder'=>'',]) }}
	<script>
		$(function(){
				$('#investigation_date').datepicker({
						format: "dd/mm/yyyy",
						todayBtn: "linked",
						keyboardNavigation: false,
						forceParse: false,
						calendarWeeks: true,
						autoclose: true
				});
		});
	</script>
{{ Form::close() }}

@endsection
