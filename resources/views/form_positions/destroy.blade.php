@extends('layouts.app2')

@section('content')
<h3>
Delete Form Position
</h3>

<br>
<h4>
{{ Form::open(['url'=>'form_positions/'.$form_position->id]) }}
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $form_position->property->property_name }}
<br>
<br>
<br>
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/form_positions?form_code={{ $form_position->form_code }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
