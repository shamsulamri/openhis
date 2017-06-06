@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id')
@endif
<h1>
<a href='{{ URL::to('form/results',[$encounter_id]) }}'>Forms</a> / {{ $form->form_name }}
</h1>
<br>
<a href='/form/{{ $form->form_code }}/{{ $patient->patient_id }}/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
<a href='/chart/line/{{ $form->form_code }}/{{ $encounter_id }}' class='btn btn-primary'><span class='fa fa-line-chart'></span> Chart</a>
<a href='/form/{{ $form->form_code }}/{{ $encounter_id }}' class='btn btn-primary'><span class='fa fa-table'></span> Table</a>
<br>
<br>
<table class="table table-hover">
	<thead>
	<tr>
		<th></th>
		<th><div align='center'>Normal Range</div></th>
		@foreach($json_values as $json)
		<th>
			<a href='/form/entry/{{ $json->value_id }}'>
					<div align='center'>
					{{ (DojoUtility::dateNoYearFormat($json->created_at)) }}
					</div>
			</a>
		</th>
		@endforeach
	</tr>
	</thead>
	<tbody>
	@foreach($properties as $property)
	<tr>
		<td class='info'>{{ $property->property->property_name }}</td>
				<td class='warning'>
					<div align='center'>
		@if (!empty($property->property->property_limit_1) && !empty($property->property->property_limit_2)) 
					{{ $property->property->property_limit_1 }} - {{ $property->property->property_limit_2 }}
		@elseif (!empty($property->property->property_limit_1) && empty($property->property->property_limit_2)) 
					< {{ $property->property->property_limit_1 }} 
		@elseif (empty($property->property->property_limit_1) && !empty($property->property->property_limit_2)) 
					> {{ $property->property->property_limit_2 }} 
		@endif
				 		{{ $property->property->property_unit }}
					</div>
				</td>
		@foreach($json_values as $json)
		<?php
			$form_values = json_decode($json->form_value,true);
			$alert=False;
		?>
	
		@if (!empty($property->property->property_limit_1) && !empty($property->property->property_limit_2)) 
				@if ($form_values[$property->property_code]>$property->property->property_limit_2) 
						<?php $alert=True; ?>	
				@endif
				@if ($form_values[$property->property_code]<$property->property->property_limit_1) 
						<?php $alert=True; ?>	
				@endif
		@endif
		@if (!empty($property->property->property_limit_1) && empty($property->property->property_limit_2)) 
				@if ($form_values[$property->property_code]>$property->property->property_limit_1) 
						<?php $alert=True; ?>	
				@endif
		@endif
		@if (empty($property->property->property_limit_1) && !empty($property->property->property_limit_2)) 
				@if ($form_values[$property->property_code]<$property->property->property_limit_2) 
						<?php $alert=True; ?>	
				@endif
		@endif
		@if (empty($form_values[$property->property_code])) 
				<?php $alert=False; ?>	
		@endif
		<td class='@if ($alert) danger @endif'>
			@if (!empty($form_values[$property->property_code]))
					<div align='center'>
							{{ $form_values[$property->property_code] }}
					</div>
			@endif
		</td>
		@endforeach
	</tr>
	@endforeach
	<tr>
		<th></th>
		<th></th>
		@foreach($json_values as $json)
		<th>
			<div align='center'>
			<a href='/form/delete/{{ $json->value_id }}' class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Remove</a>
			</div>
		</th>
		@endforeach
	</tr>
	</tbody>
</table>

@endsection
