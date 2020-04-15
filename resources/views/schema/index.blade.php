<h1>Index
<a href='/schema/form/{{ $table }}' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/race/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form> <br>

@if ($records->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($records as $record)
	<tr>
			<td>
					<a href='{{ URL::to('schema/form/'.$table.'/'. $record->id) }}'>
						{{ $record->id }}
					</a>
			</td>
			<td>
					{{ $record->id }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('schema/delete/'.$table.'/'. $record->id ) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $records->appends(['search'=>$search])->render() }}
	@else
	{{ $records->render() }}
@endif
<br>
@if ($records->total()>0)
	{{ $records->total() }} records found.
@else
	No record found.
@endif
