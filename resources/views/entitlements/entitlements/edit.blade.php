@extends('layouts.app')

@section('content')
<h1>
Edit Entitlement
</h1>
@include('common.errors')
<br>
{{ Form::model($entitlement, ['route'=>['entitlements.update',$entitlement->entitlement_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>entitlement_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('entitlement_code', $entitlement->entitlement_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('entitlements.entitlement')
{{ Form::close() }}

@endsection
