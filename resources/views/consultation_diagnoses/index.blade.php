@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Diagnoses</h1>
<br>
<a href='/consultation_diagnoses/create' class='btn btn-primary'>Create</a>
<br>
<br>
<?php $hasPrincipal=False; ?>
@if ($consultation_diagnoses->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
	<th></th>
    <th>Diagnosis</th> 
    <th>Date</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($consultation_diagnoses as $consultation_diagnosis)
	<tr>
			<td width='5%'>
			@if ($consultation_diagnosis->diagnosis_is_principal)
				<?php $hasPrincipal=True; ?>
				<div class='label label-primary' title='Princiapl Diagnosis'>
				1°	
				</div>
			@else
				<div class='label label-default' title='Secondary Diagnosis'>
				2°	
				</div>
			@endif
			</td>
			<td>
					<a href='{{ URL::to('consultation_diagnoses/'. $consultation_diagnosis->id . '/edit') }}'>
						{{$consultation_diagnosis->diagnosis_clinical}}
					</a>
			</td>
			<td class='col-xs-3'>
					{{ (DojoUtility::dateLongFormat($consultation_diagnosis->created_at)) }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('consultation_diagnoses/delete/'. $consultation_diagnosis->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (!$hasPrincipal)
	<div class='alert alert-warning' role='alert'>
	Please define a principal diagnosis.
	</div>
@endif
@if (isset($search)) 
	{{ $consultation_diagnoses->appends(['search'=>$search])->render() }}
	@else
	{{ $consultation_diagnoses->render() }}
@endif
<br>
@if ($consultation_diagnoses->total()>0)
	{{ $consultation_diagnoses->total() }} records found.
@else
	No record found.
@endif
@endsection
