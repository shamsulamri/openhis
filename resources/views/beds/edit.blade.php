@extends('layouts.app')

@section('content')
<h1>
Edit Bed
</h1>

<br>
{{ Form::model($bed, ['route'=>['beds.update',$bed->bed_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('bed_code')) has-error @endif'>
        <label for='bed_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('bed_code', $bed->bed_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@can('module-ward')
		@can('system-administrator')
			@include('beds.bed')
		@else
			@include('beds.bed_status')
		@endcan
	@else
			@include('beds.bed')
	@endcan
{{ Form::close() }}

@endsection
