@extends('layouts.app')

@section('content')
<h1>
Delete Form System
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $form_system->system_name }}
{{ Form::open(['url'=>'form_systems/'.$form_system->system_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/form_systems" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
