@extends('layouts.app')

@section('content')
<h1>
Loan {{ ucwords($loan->loan_code) }} 
</h1>
@include('common.errors')
<h3>
Your request has been submitted.
</h3>
@endsection
