@extends('layouts.app')

@section('content')
<h1>
Delete Drug Caution
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $drug_caution->caution_code }}
{{ Form::open(['url'=>'drug_cautions/'.$drug_caution->caution_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/drug_cautions" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
