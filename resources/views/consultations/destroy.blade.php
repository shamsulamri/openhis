@extends('layouts.app')

@section('content')
<h1>
Delete Consultation
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $consultation->consultation_status }}
{{ Form::open(['url'=>'consultations/'.$consultation->consultation_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/consultations" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
