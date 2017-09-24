@extends('layouts.app')

@section('content')
<h1>Patient Enquiry</h1>
<form action='/patient/enquiry' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Enter patient MRN" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@endsection
