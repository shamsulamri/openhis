@extends('layouts.app')

@section('content')
<h1>
Delete Medical Certificate
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $medical_certificate->encounter_id }}
{{ Form::open(['url'=>'medical_certificates/'.$medical_certificate->mc_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/medical_certificates" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
