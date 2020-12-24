@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Clinical Procedures</h1>
@if (!$has_principal)
	<div class='alert alert-warning' role='alert'>
	Please define principal procedure.
	</div>
@else
	<br>
@endif
<a href='/consultation_procedures/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($consultation_procedures->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
	<th></th>
    <th>Procedure</th>
    <th>Date</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($consultation_procedures as $consultation_procedure)
	<tr>
			<td width='5%'>
			@if ($consultation_procedure->procedure_is_principal)
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
					<a href='{{ URL::to('consultation_procedures/'. $consultation_procedure->id . '/edit') }}'>
						{{$consultation_procedure->procedure_description}}
					</a>
			</td>
			<td class='col-xs-3'>
					{{ (DojoUtility::dateLongFormat($consultation_procedure->created_at)) }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('consultation_procedures/delete/'. $consultation_procedure->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>


@if (isset($search)) 
	{{ $consultation_procedures->appends(['search'=>$search])->render() }}
	@else
	{{ $consultation_procedures->render() }}
@endif
<br>
@if ($consultation_procedures->total()>0)
	{{ $consultation_procedures->total() }} records found.
@else
	No record found.
@endif
@endsection
