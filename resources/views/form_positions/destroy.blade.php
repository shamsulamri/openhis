@extends('layouts.app2')

@section('content')
<h1>
Delete Form Position
</h1>

<br>
<h3>
{{ Form::open(['url'=>'form_positions/'.$form_position->id, 'class'=>'pull-right']) }}
Are you sure you want to delete the selected record ?
<br>
{{ $form_position->property_code }}
<br>
<br>
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/form_positions?form_code={{ $form_position->form_code }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
