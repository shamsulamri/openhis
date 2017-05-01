@extends('layouts.app')

@section('content')
@include('patients.id')
<div class='row'>
@include('charts.line')
</div>
@endsection
