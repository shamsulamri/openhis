@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Diagnoses</h1>
@include('common.notification')
<a href='/consultation_diagnoses/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($consultation_diagnoses->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Diagnosis</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($consultation_diagnoses as $consultation_diagnosis)
	<tr>
			<td class='col-xs-3'>
					{{ date('d F Y', strtotime($consultation_diagnosis->created_at)) }}
			</td>
			<td>
					<a href='{{ URL::to('consultation_diagnoses/'. $consultation_diagnosis->id . '/edit') }}'>
						{{$consultation_diagnosis->diagnosis_clinical}}
					</a>
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('consultation_diagnoses/delete/'. $consultation_diagnosis->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
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
