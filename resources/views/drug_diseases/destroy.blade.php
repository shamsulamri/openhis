@extends('layouts.app')

@section('content')
<h1>
Delete Drug Disease
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $drug_disease->drug_code }}
{{ Form::open(['url'=>'drug_diseases/'.$drug_disease->disease_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/drug_diseases" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
