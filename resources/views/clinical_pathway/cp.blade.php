@extends('layouts.app')

@section('content')
@include('consultations.panel')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
<h1>Clinical Pathway</h1>
<iframe 
       src="http://localhost:8084/cp"
       width="100%"
       height="800">
@endsection
