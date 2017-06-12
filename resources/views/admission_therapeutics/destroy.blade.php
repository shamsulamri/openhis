@extends('layouts.app')

@section('content')
<h1>
Delete Admission Therapeutic
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $admission_therapeutic->admission_id }}
{{ Form::open(['url'=>'admission_therapeutics/'.$admission_therapeutic->admission_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/admission_therapeutics" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
