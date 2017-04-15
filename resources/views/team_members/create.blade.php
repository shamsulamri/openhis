@extends('layouts.app')

@section('content')
<h1>
New Team Member
</h1>
@include('common.errors')
<br>
{{ Form::model($team_member, ['url'=>'team_members', 'class'=>'form-horizontal']) }} 
    
	@include('team_members.team_member')
{{ Form::close() }}

@endsection
