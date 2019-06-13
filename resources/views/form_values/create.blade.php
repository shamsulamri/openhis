@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id_only')
@endif
<div class="row">
  <div class="col-md-12">
<h1>
{{ $form->form_name }}
</h1>
<br>
{{ Form::model(null, ['id'=>'myForm','url'=>'form/entry', 'class'=>'form-horizontal']) }} 
@foreach ($properties as $property)
	<?php
			$value = null;
			if (!empty($json[$property->property_code])) {
				$value = $json[$property->property_code];
			}
	?>
    <div class='form-group'>
				@if ($property->property->property_type == "header")
					<div class='col-sm-12'>
					<br>
					<h3>
					{{ $property->property->property_name }}
					<hr>
					</h3>
					</div>
				@else
        <label for='{{ $property->property->property_code }}' class='col-sm-3 control-label'>{{ $property->property->property_name }}</label>
				@endif
        <div class='col-sm-3'>
				@if ($property->property->property_type == "text")
					{{ Form::text($property->property->property_code, $value, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
				@endif
				@if ($property->property->property_type == "textarea")
					{{ Form::textarea($property->property->property_code, $value, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
				@endif
				@if ($property->property->property_type == "number")
					<input class='form-control' id="{{ $property->property->property_code }}" onkeypress="return isNumberKey(event)" type="text" name="{{ $property->property->property_code }}" value="{{ $value }}">
				@endif
				@if ($property->property->property_type == "list")
					<?php 
						$list = explode(";",$property->property->property_list);
						$list = array_combine($list, $list);
					?>
						{{ Form::select($property->property->property_code, $list, $value) }}
				@endif
				@if ($property->property->property_type == "date")
						<input data-mask="99/99/9999" name="{{ $property->property->property_code }}" id="{{ $property->property->property_code }}" type="text" class="form-control" value="{{ $value }}">
				@endif
        </div>
        <div class='col-sm-2'>
			@if ($property->property->unitMeasure)
				<label class='control-label'>{{ $property->property->unitMeasure->unit_shortname }}</label>
			@endif
        </div>
    </div>
@endforeach

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@if (!empty($is_create))
            <a class="btn btn-default" href="/form/results/{{ $encounter_id }}" role="button">Cancel</a>
			@else
            <a class="btn btn-default" href="/form/{{ $form->form_code }}/{{ $encounter_id }}" role="button">Cancel</a>
			@endif
				
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('form_code', $form->form_code) }}
	{{ Form::hidden('encounter_id', $encounter_id) }}
	{{ Form::hidden('value_id', $value_id) }}
{{ Form::close() }}
	<script>
	</div>
</div>
	$("#myForm").validate();
@foreach ($properties as $property)
	@if ($property->property->property_type == "date")
		$('#{{ $property->property->property_code }}').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
	@endif
@endforeach
function isNumberKey(evt)
       {
			             var charCode = (evt.which) ? evt.which : evt.keyCode;
						           if (charCode != 46 && charCode > 31 
										               && (charCode < 48 || charCode > 57))
													                return false;

          return true;
       }
	</script>
@endsection
