@extends('layouts.app')

@section('content')
<h1>
Edit General Ledger
</h1>
@include('common.errors')
<br>
{{ Form::model($general_ledger, ['route'=>['general_ledgers.update',$general_ledger->gl_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>gl_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('gl_code', $general_ledger->gl_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('general_ledgers.general_ledger')
{{ Form::close() }}

@endsection
