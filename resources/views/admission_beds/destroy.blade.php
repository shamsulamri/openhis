@extends('layouts.app')

@section('content')
<h1>
Delete Admission Bed
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $admission_bed->bed_name }}
{{ Form::open(['url'=>'admission_beds/'.$admission_bed->bed_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/admission_beds" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
