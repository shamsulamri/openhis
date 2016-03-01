@extends('layouts.app')

@section('content')
<h1>
Delete Drug Frequency
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $drug_frequency->frequency_name }}
{{ Form::open(['url'=>'drug_frequencies/'.$drug_frequency->frequency_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/drug_frequencies" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
