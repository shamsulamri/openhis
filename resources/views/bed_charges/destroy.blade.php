@extends('layouts.app')

@section('content')
<h1>
Delete Bed Charge
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $bed_charge->bed_code }}
{{ Form::open(['url'=>'bed_charges/'.$bed_charge->charge_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bed_charges" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
