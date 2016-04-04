@extends('layouts.app')

@section('content')
<h1>
Edit Supplier
</h1>
@include('common.errors')
<br>
{{ Form::model($supplier, ['route'=>['suppliers.update',$supplier->supplier_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>supplier_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('supplier_code', $supplier->supplier_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('suppliers.supplier')
{{ Form::close() }}

@endsection