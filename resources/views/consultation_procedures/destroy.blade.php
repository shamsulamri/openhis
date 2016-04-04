@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
<h2>
Delete Procedure
</h2>
@include('common.errors')
<br>
<h4>
Are you sure you want to delete the selected record ?
{{ $consultation_procedure->procedure_description }}
{{ Form::open(['url'=>'consultation_procedures/'.$consultation_procedure->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/consultation_procedures" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
