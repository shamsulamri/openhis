@extends('layouts.app')

@section('content')
<h1>
Delete Credit Card
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $credit_card->card_name }}
{{ Form::open(['url'=>'credit_cards/'.$credit_card->card_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/credit_cards" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
