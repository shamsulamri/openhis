@extends('layouts.app2')

@section('content')
<h1>
Delete Form Property
</h1>

<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $form_property->property_name }}
{{ Form::open(['url'=>'form_properties/'.$form_property->property_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/form_properties" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
