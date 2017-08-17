@extends('layouts.app')

@section('content')
<h1>
Delete Drug Special Instruction
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $drug_special_instruction->special_instruction_english }}
{{ Form::open(['url'=>'drug_special_instructions/'.$drug_special_instruction->special_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/drug_special_instructions" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
