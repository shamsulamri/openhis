@extends('layouts.app')

@section('content')
<h1>
Delete Drug Dosage
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $drug_dosage->dosage_name }}
{{ Form::open(['url'=>'drug_dosages/'.$drug_dosage->dosage_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/drug_dosages" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
