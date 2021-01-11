@extends('layouts.app')

@section('content')
<h1>Block Date List
<a href='/block_dates/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/block_date/search' method='post' class='form-horizontal'>
	<div class='input-group'>
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Find</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Search in description" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Service</div></label>
						<div class='col-sm-9'>
							{{ Form::select('service_id', $services,$service_id, ['id'=>'service_id','class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<div class='col-sm-9'>
								<span class='input-group-btn'>
									<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
								</span>
						</div>
					</div>
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($block_dates->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Description</th>
    <th>Service</th> 
    <th>Start</th> 
    <th>End</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($block_dates as $block_date)
	<tr>
			<td>
					<a href='{{ URL::to('block_dates/'. $block_date->block_id . '/edit') }}'>
						{{$block_date->block_name}}
					</a>
			</td>
			<td>
					@if (!empty($block_date->service))
						{{$block_date->service->service_name }}
					@endif
			</td>
			<td>
					{{ (DojoUtility::dateLongFormat($block_date->block_date)) }}
			</td>
			<td>
					{{ (DojoUtility::dateLongFormat($block_date->block_date_end)) }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('block_dates/delete/'. $block_date->block_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $block_dates->appends(['search'=>$search])->render() }}
	@else
	{{ $block_dates->render() }}
@endif
<br>
@if ($block_dates->total()>0)
	{{ $block_dates->total() }} records found.
@else
	No record found.
@endif
@endsection
