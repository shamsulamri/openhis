
@extends('layouts.app')

@section('content')
@include('products.id')
@include('patients.id')
<h1>
{{ $title }}
</h1>

@include('common.errors')
<br>
{{ Form::model($loan, ['url'=>$url, 'class'=>'form-horizontal']) }} 
	@if ($loan->loan_code=='lend')
    <div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-2 control-label'>Status<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('loan_code', $loan->status->loan_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Ward<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('loan_code', $loan->ward->ward_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('loan_quantity')) has-error @endif'>
        <label for='loan_quantity' class='col-sm-2 control-label'>Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('loan_quantity', $loan->loan_quantity, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
	@else
	<!--
	<div class='form-group  @if ($errors->has('item_code')) has-error @endif'>
        <label for='item_code' class='col-sm-2 control-label'>Item<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('item_code', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('item_code')) <p class="help-block">{{ $errors->first('item_code') }}</p> @endif
        </div>
    </div>
	-->
    <div class='form-group  @if ($errors->has('loan_code')) has-error @endif'>
        <label for='loan_code' class='col-sm-2 control-label'>Status<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('loan_code', $loan_status,null, ['id'=>'loan_code','class'=>'form-control','onchange'=>'statusChanged()']) }}
            @if ($errors->has('loan_code')) <p class="help-block">{{ $errors->first('loan_code') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Ward<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('ward_code', $wards,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>
	@if (!$is_folder)
    <div class='form-group  @if ($errors->has('loan_quantity')) has-error @endif'>
        <label for='loan_quantity' class='col-sm-2 control-label'>Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('loan_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('loan_quantity')) <p class="help-block">{{ $errors->first('loan_quantity') }}</p> @endif
        </div>
    </div>
	@endif
	@endif


    <div class='form-group  @if ($errors->has('loan_description')) has-error @endif'>
        <label for='loan_description' class='col-sm-2 control-label'>Description</label>
        <div class='col-sm-10'>
            {{ Form::textarea('loan_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('loan_description')) <p class="help-block">{{ $errors->first('loan_description') }}</p> @endif
        </div>
    </div>

	@if (!$is_folder && $loan->loan_code != 'exchange')
    <div class='form-group  @if ($errors->has('loan_recur')) has-error @endif'>
        {{ Form::label('loan_recur', 'Recur Daily',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('loan_recur', '1') }}
            @if ($errors->has('loan_recur')) <p class="help-block">{{ $errors->first('loan_recur') }}</p> @endif
        </div>
    </div>
	@endif

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/loans/ward" role="button">Cancel</a>
			@if ($loan->loan_code!='lend')
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
			@endif
        </div>
    </div>

	{{ Form::hidden('item_code', $loan->item_code) }}
	{{ Form::hidden('loan_request_by', null) }}
	@if ($is_folder)
		{{ Form::hidden('loan_is_folder',1) }}
	@endif
		
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
