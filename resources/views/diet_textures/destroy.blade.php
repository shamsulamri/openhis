@extends('layouts.app')

@section('content')
<h1>
Delete Diet Texture
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $diet_texture->texture_name }}
{{ Form::open(['url'=>'diet_textures/'.$diet_texture->texture_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/diet_textures" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
