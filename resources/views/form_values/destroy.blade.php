@extends('layouts.app')

@section('content')
<h1>
Delete Form Value
</h1>
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ Form::open(['url'=>'form/'.$form_value->value_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/form/{{ $form_value->form_code }}/{{ $form_value->encounter_id }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
