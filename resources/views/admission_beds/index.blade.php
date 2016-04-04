@extends('layouts.app')

@section('content')
@if ($admission != NULL)
	@include('patients.id')
@endif 	
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<h2>Admission Bed Index</h2>
<br>
<form action='/admission_bed/search' method='post'>
	{{ Form::select('wards', $ward, $ward_code, ['class'=>'form-control']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<br>
	{{ Form::submit('Refresh', ['class'=>'btn btn-primary']) }}
	{{ Form::hidden('admission_id', $admission->admission_id) }}
</form>
<br>
@if ($admission_beds->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Bed</th>
	<th>Vacancy</th>
	</tr>
  </thead>
	<tbody>
@foreach ($admission_beds as $admission_bed)
	<tr>
			<td>
					<a href='{{ URL::to('admission_beds/'. $admission_bed->bed_code . '/edit') }}'>
						{{$admission_bed->bed_name}}
					</a>
			</td>
			<td>
					{{$admission_bed->patient_name}}
			</td>
			<td align='right'>
					@if (empty($admission_bed->patient_name))
					<a class='btn btn-primary btn-xs' href='{{ URL::to('admission_beds/admit/'.$admission->admission_id.'/'. $admission_bed->bed_code) }}'>Select</a>
					@endif
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $admission_beds->appends(['search'=>$search])->render() }}
	@else
	{{ $admission_beds->render() }}
@endif
<br>
@if ($admission_beds->total()>0)
	{{ $admission_beds->total() }} records found.
@else
	No record found.
@endif
@endsection