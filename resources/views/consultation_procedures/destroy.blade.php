@extends('layouts.app')

@section('content')
<h1>
Delete Consultation Procedure
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $consultation_procedure->procedure_description }}
{{ Form::open(['url'=>'consultation_procedures/'.$consultation_procedure->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/consultation_procedures" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
