@extends('layouts.app')

@section('content')
<h1>
Delete Block Type
</h1>
<br>
<h3>
Are you sure you want to delete the selected record ?
'{{ $block_type->block_name }}'
{{ Form::open(['url'=>'block_types/'.$block_type->block_code, 'class'=>'pull-right']) }}
		{{ method_field('DELETE') }}
		<a class="btn btn-default" href="/block_types" role="button">Cancel</a>
		{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
