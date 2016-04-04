@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/consultation_procedures/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($consultation_procedures->total()>0)
<table class="table table-hover">
	<tbody>
@foreach ($consultation_procedures as $consultation_procedure)
	<tr>
			<td class='col-xs-2'>
					{{ date('d F Y', strtotime($consultation_procedure->created_at)) }}
			</td>
			<td>
					<a href='{{ URL::to('consultation_procedures/'. $consultation_procedure->id . '/edit') }}'>
						{{$consultation_procedure->procedure_description}}
					</a>
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
