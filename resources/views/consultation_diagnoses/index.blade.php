@extends('layouts.app')

@section('content')
<h1>Consultation Diagnosis Index</h1>
<br>
<form action='/consultation_diagnosis/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/consultation_diagnoses/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($consultation_diagnoses->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($consultation_diagnoses as $consultation_diagnosis)
	<tr>
			<td>
					<a href='{{ URL::to('consultation_diagnoses/'. $consultation_diagnosis->id . '/edit') }}'>
						{{$consultation_diagnosis->diagnosis_clinical}}
					</a>
			</td>
			<td>
					{{$consultation_diagnosis->id}}
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
