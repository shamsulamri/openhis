@extends('layouts.app')

@section('content')
<h1>
Delete Drug Prescription
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $drug_prescription->drug_code }}
{{ Form::open(['url'=>'drug_prescriptions/'.$drug_prescription->prescription_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/drug_prescriptions" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
