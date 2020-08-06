@extends('layouts.app')

@section('content')
<h1>
Edit Discount Rule
</h1>
@include('common.errors')
<br>
{{ Form::model($discount_rule, ['route'=>['discount_rules.update',$discount_rule->rule_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('discount_rules.discount_rule')
{{ Form::close() }}

@endsection
