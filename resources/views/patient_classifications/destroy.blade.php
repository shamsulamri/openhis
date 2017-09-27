@extends('layouts.app')

@section('content')
<h1>
Delete Patient Classification
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $patient_classification->class_name }}
{{ Form::open(['url'=>'patient_classifications/'.$patient_classification->class_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/patient_classifications" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
