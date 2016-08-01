@extends('layouts.app2')

@section('content')
<h3>Relationships</h3>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($dependants->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Relationship</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($dependants as $dependant)
	<tr>
			<td>
					<a href='{{ URL::to('dependants/'. $dependant->patient_id . '/edit?patient_id='.$patient_id) }}'>
						{{ strtoupper($dependant->patient_name) }}
					</a>
			</td>
			<td>
					{{$dependant->relation_name}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='patient_dependants/delete/{{ $dependant->id }}'>-</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $dependants->appends(['search'=>$search])->render() }}
	@else
	{{ $dependants->render() }}
@endif
<br>
@if ($dependants->total()>0)
	{{ $dependants->total() }} records found.
@else
	No record found.
@endif
@endsection
