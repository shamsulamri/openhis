@extends('layouts.app')

@section('content')
<h1>Credit Card Index
<a href='/credit_cards/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/credit_card/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($credit_cards->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>card_name</th>
    <th>card_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($credit_cards as $credit_card)
	<tr>
			<td>
					<a href='{{ URL::to('credit_cards/'. $credit_card->card_code . '/edit') }}'>
						{{$credit_card->card_name}}
					</a>
			</td>
			<td>
					{{$credit_card->card_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('credit_cards/delete/'. $credit_card->card_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $credit_cards->appends(['search'=>$search])->render() }}
	@else
	{{ $credit_cards->render() }}
@endif
<br>
@if ($credit_cards->total()>0)
	{{ $credit_cards->total() }} records found.
@else
	No record found.
@endif
@endsection
