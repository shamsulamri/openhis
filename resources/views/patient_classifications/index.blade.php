@extends('layouts.app')

@section('content')
<h1>Patient Classification Index
<a href='/patient_classifications/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/patient_classification/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($patient_classifications->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>classification_name</th>
    <th>classification_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($patient_classifications as $patient_classification)
	<tr>
			<td>
					<a href='{{ URL::to('patient_classifications/'. $patient_classification->classification_code . '/edit') }}'>
						{{$patient_classification->classification_name}}
					</a>
			</td>
			<td>
					{{$patient_classification->classification_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('patient_classifications/delete/'. $patient_classification->classification_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $patient_classifications->appends(['search'=>$search])->render() }}
	@else
	{{ $patient_classifications->render() }}
@endif
<br>
@if ($patient_classifications->total()>0)
	{{ $patient_classifications->total() }} records found.
@else
	No record found.
@endif
@endsection
