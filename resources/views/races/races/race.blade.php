@extends('layouts.app')

@section('content')
<h1>
@if (empty($[singluar])
	New Race
@else
	Edit Race
@endif
</h1>
<br>

@if (empty($race)
	{{ Form::model($race, ['url'=>'races', 'class'=>'form-horizontal']) }} 
@else
	{{ Form::model($race, ['route'=>['races.update',$race->race_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
@endif
    
    
        
    <div class='form-group @if ($errors->has('race_code')) has-errors @endif'>
          <label>race_code<span style='color:red;'> *</span></label>
          @if (empty($project))
          {{ Form::text('race_code', null, ['class'=>'form-control col-sm-12','placeholder'=>'', 'maxlength'=>'20.0']) }}
          @else
          {{ Form::label('race_code', $project->project_code, ['class'=>'form-control col-sm-12']) }}
          @endif
    </div>
    
    
	
    <div class='form-group  @if ($errors->has('race_name')) has-error @endif'>
        <label for='race_name' class='col-sm-2 control-label'>race_name<span style='color:red;'> *</span></label>
        {{ Form::text('race_name', null, ['class'=>'form-control col-sm-12','placeholder'=>'','maxlength'=>'200']) }}
        @if ($errors->has('race_name')) <p class="help-block">{{ $errors->first('race_name') }}</p> @endif
    </div>

    <div class='form-group  @if ($errors->has('deleted_at')) has-error @endif'>
        {{ Form::label('deleted_at', 'deleted_at',['class'=>'col-sm-12 form-control']) }}
        {{ Form::text('deleted_at', null, ['class'=>'form-control col-sm-12','placeholder'=>'',]) }}
        @if ($errors->has('deleted_at')) <p class="help-block">{{ $errors->first('deleted_at') }}</p> @endif
    </div>

    <div class='form-group'>
        <a class="btn btn-secondary" href="/races" role="button">Cancel</a>
        {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
    </div>


{{ Form::close() }}

@endsection
