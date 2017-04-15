@extends('layouts.app')

@section('content')
<h1>
Edit Team Member
</h1>
@include('common.errors')
<br>
{{ Form::model($team_member, ['route'=>['team_members.update',$team_member->member_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('team_members.team_member')
{{ Form::close() }}

@endsection
