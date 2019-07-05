@extends('layouts.app')

@section('content')
<h1>
Edit History
</h1>
@include('common.errors')
<br>
{{ Form::model($history, ['route'=>['histories.update',$history->history_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>history_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('history_code', $history->history_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('histories.history')
{{ Form::close() }}

@endsection
