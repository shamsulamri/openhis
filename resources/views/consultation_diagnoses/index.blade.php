@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
@if (Session::has('message'))
	<br>
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/consultation_diagnoses/create/{{ $consultation->consultation_id }}' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($consultation_diagnoses->total()>0)
<table class="table table-hover">
	<tbody>
@foreach ($consultation_diagnoses as $consultation_diagnosis)
	<tr>
			<td class='col-xs-2'>
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
