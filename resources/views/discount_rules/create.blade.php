@extends('layouts.app')

@section('content')
<h1>
New Discount Rule
</h1>
@include('common.errors')
<br>
{{ Form::model($discount_rule, ['url'=>'discount_rules', 'class'=>'form-horizontal']) }} 
	@include('discount_rules.discount_rule')
{{ Form::close() }}

@endsection
