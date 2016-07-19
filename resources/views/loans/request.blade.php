
@extends('layouts.app')

@section('content')
@include('products.id')
<h1>
New Loan Status
</h1>
@include('common.errors')
<br>
{{ Form::model($loan, ['url'=>'loans/request/'.$product->product_code, 'class'=>'form-horizontal']) }} 

    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Ward<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('ward_code', $ward,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_quantity')) has-error @endif'>
        <label for='loan_quantity' class='col-sm-2 control-label'>Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('loan_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('loan_quantity')) <p class="help-block">{{ $errors->first('loan_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_description')) has-error @endif'>
        <label for='loan_description' class='col-sm-2 control-label'>Description</label>
        <div class='col-sm-10'>
            {{ Form::textarea('loan_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('loan_description')) <p class="help-block">{{ $errors->first('loan_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_recur')) has-error @endif'>
        {{ Form::label('loan_recur', 'Recur Daily',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('loan_recur', '1') }}
            @if ($errors->has('loan_recur')) <p class="help-block">{{ $errors->first('loan_recur') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/loans" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('item_code', $item_code) }}
	{{ Form::hidden('loan_request_by', null) }}
	{{ Form::hidden('loan_code', null) }}

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

{{ Form::close() }}
@endsection
