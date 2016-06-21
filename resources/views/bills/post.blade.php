@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Bill Posted
</h1>
<h3>
The bill has been successfully posted.
</h3>
<br>
<a href='/bill_items/{{ $encounter->encounter_id }}' class='btn btn-default'>Return</a>
@endsection
