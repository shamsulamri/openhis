@extends('layouts.app')

@section('content')
<h1>
Delete Drug Indication
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $drug_indication->indication_description }}
{{ Form::open(['url'=>'drug_indications/'.$drug_indication->indication_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/drug_indications" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
