@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Medical Record Documents</h1>
<br>
<form action='/document/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a class="btn btn-default" href="/patients/{{ $patient->patient_id }}" role="button">Return</a>
<a href='/documents/create?patient_mrn={{ $patient->patient_mrn }}' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($documents->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Id</th> 
    <th>Document</th>
    <th>Description</th>
    <th>Status</th>
	<th>Created At</th>
	@can('module-medical-record')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($documents as $document)
	<tr>
			<td>
					{{$document->document_id}}
			</td>
			<td>
					<a href='{{ URL::to('documents/'. $document->document_id . '/edit') }}'>
						{{$document->document->type_name}}
					</a>
			</td>
			<td>
						{{$document->document_description}}
			</td>
			<td>
						{{$document->status->status_name}}
			</td>
			<td>
						{{ date('d F Y, H:i', strtotime($document->created_at )) }}
			</td>
			@can('module-medical-record')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('documents/delete/'. $document->document_id) }}'>Delete</a>
			</td>
			@endcan
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
