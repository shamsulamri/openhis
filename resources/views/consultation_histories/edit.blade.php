@extends('layouts.app')

@section('content')
<h1>
Edit Consultation History
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation_history, ['route'=>['consultation_histories.update',$consultation_history->history_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_histories.consultation_history')
{{ Form::close() }}

@endsection
