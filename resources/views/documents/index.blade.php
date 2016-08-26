@extends('layouts.app')

@section('content')
@can('module-medical-record')
		@include('patients.id')
@else
		@if ($consultation)
		@include('consultations.panel')
		@endif
@endcan
<h1>Medical Record Documents</h1>
<!--
<br>
<form action='/document/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	{{ Form::hidden('patient_mrn', $patient->patient_mrn) }}
</form>
-->
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@can('module-medical-record')
@if ($loan_flag)
<a class="btn btn-default" href="/loans?type=folder" role="button">Return</a>
@else
<a class="btn btn-default" href="/patients/{{ $patient->patient_id }}" role="button">Return</a>
@endif
<a href='/documents/create?patient_mrn={{ $patient->patient_mrn }}' class='btn btn-primary'>Create</a>
<br>
<br>
@endcan
@if ($documents->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Document</th>
    <th>Description</th>
    <th>Status</th>
	<th>Date</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($documents as $document)
	<tr>
			<td>
					@can('module-medical-record')
					<a href='{{ URL::to('documents/'. $document->document_id . '/edit') }}'>
						{{$document->document->type_name}}
					</a>
					@else
						{{$document->document->type_name}}
					@endcan
			</td>
			<td>
						{{$document->document_description}}
			</td>
			<td>
						{{$document->status->status_name}}
			</td>
			<td>
						{{ date('d F Y', strtotime($document->created_at )) }}
			</td>
			<td align='right'>
				@if (!empty($document->document_file))
					<a class='btn btn-primary btn-xs' href='{{ URL::to('documents/file/'. $document->document_uuid) }}'>View File</a>
				@endif
				@can('module-medical-record')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('documents/delete/'. $document->document_id) }}'>Delete</a>
				@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $documents->appends(['search'=>$search])->render() }}
	@else
	{{ $documents->render() }}
@endif
<br>
@if ($documents->total()>0)
	{{ $documents->total() }} records found.
@else
	No record found.
@endif
@endsection
