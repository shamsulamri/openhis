
@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
<h1>{{ $form->form_name }}</h1>
<div class="row">
	<div class="col-xs-6">
		<h3>Property List</h3>
		<iframe name='frameIndex' id='frameIndex' width='100%' height='800px' src='/form_properties?form_code={{ $form->form_code }}'></iframe>
	</div>
	<div class="col-xs-6">
		<h3>Form Properties</h3>
		<iframe name='frameLine' id='frameLine' width='100%' height='800px' src='/form_positions?form_code={{ $form->form_code }}'><iframe>
	</div>
</div>
@endsection
