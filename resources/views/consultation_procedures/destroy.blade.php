@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>
Delete Procedure
</h1>
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
