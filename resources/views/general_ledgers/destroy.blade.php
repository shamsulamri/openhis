@extends('layouts.app')

@section('content')
<h1>
Delete General Ledger
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $general_ledger->gl_name }}
{{ Form::open(['url'=>'general_ledgers/'.$general_ledger->gl_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/general_ledgers" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
