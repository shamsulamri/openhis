@extends('layouts.app')

@section('content')
<h1>
Edit Diet Complain
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_complain, ['route'=>['diet_complains.update',$diet_complain->complain_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('diet_complains.diet_complain')
{{ Form::close() }}

@endsection
