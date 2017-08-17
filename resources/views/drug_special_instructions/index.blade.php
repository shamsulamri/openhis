@extends('layouts.app')

@section('content')
<h1>Drug Special Instruction List
<a href='/drug_special_instructions/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/drug_special_instruction/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($drug_special_instructions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th> 
    <th>Instruction</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_special_instructions as $drug_special_instruction)
	<tr>
			<td>
					{{$drug_special_instruction->special_code}}
			</td>
			<td>
					<a href='{{ URL::to('drug_special_instructions/'. $drug_special_instruction->special_code . '/edit') }}'>
						{{$drug_special_instruction->special_instruction_english}}
					</a>
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drug_special_instructions/delete/'. $drug_special_instruction->special_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drug_special_instructions->appends(['search'=>$search])->render() }}
	@else
	{{ $drug_special_instructions->render() }}
@endif
<br>
@if ($drug_special_instructions->total()>0)
	{{ $drug_special_instructions->total() }} records found.
@else
	No record found.
@endif
@endsection
