@extends('layouts.app2')

@section('content')
<form action='/form_property/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<!--
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
			-->
<a href='/form_properties/create?form_code={{ $form_code }}' class='btn btn-md btn-primary'><span class='glyphicon glyphicon-plus'></span></a>
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name="form_code" value="{{ $form_code }}">
</form>
<br>

@if ($form_properties->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
    <th>System</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($form_properties as $form_property)
	<tr>
			<td>
					<a href='{{ URL::to('form_properties/'. $form_property->property_code . '/edit?form_code='.$form_code) }}'>
						{{$form_property->property_name}}
					</a>
			</td>
			<td>
					{{$form_property->property_code}}
			</td>
			<td>
					{{$form_property->system_code}}
			</td>
			<td align='right'>
					<a class='btn btn-primary btn-xs' href='{{ URL::to('form_property/add/'.$form_code.'/'.$form_property->property_code) }}'><span class='glyphicon glyphicon-chevron-right'></span></a>
					@can('system-administrator')
					<!--
					<a class='btn btn-danger btn-xs' href='{{ URL::to('form_properties/delete/'. $form_property->property_code) }}'>-</a>
					-->
					@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $form_properties->appends(['search'=>$search, 'form_code'=>$form_code])->render() }}
	@else
	{{ $form_properties->appends(['form_code'=>$form_code])->render() }}
@endif
<br>
@if ($form_properties->total()>0)
	{{ $form_properties->total() }} records found.
@else
	No record found.
@endif
@if (Session::has('message'))
<script>
	var frameLine = parent.document.getElementById('frameLine');
	frameLine.src='/form_positions?form_code={{ $form_code }}';
</script>
@endif
@endsection
