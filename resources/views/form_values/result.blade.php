@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id')
@endif

<div class="row">
  <div class="col-md-6">
		<h1>Form List</h1>
		<form action='/form/search_form/{{ $encounter_id }}' method='post'>
			<div class='input-group'>
				<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
				<span class='input-group-btn'>
					<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
				</span>
			</div>
			<input type='hidden' name="_token" value="{{ csrf_token() }}">
			<input type='hidden' name="encounter_id" value="{{ $encounter_id }}">
		</form>
		<br>

		@if ($forms->total()>0)
		<table class="table table-hover">
			<tbody>
		@foreach ($forms as $form)
			<tr>
					<td>
							{{$form->form_name}}
					</td>
					<td width='10'>
							<a class='btn btn-primary btn-xs' href='{{ URL::to('form/'. $form->form_code.'/'.$patient->patient_id.'/create') }}'>
								<span class='glyphicon glyphicon-plus'></span>
							</a>
					</td>
			</tr>
		@endforeach
		@endif
		</tbody>
		</table>
		@if (isset($search)) 
			{{ $forms->appends(['search'=>$search, 'encounter_id'=>$encounter_id])->render() }}
			@else
			{{ $forms->render() }}
		@endif
		<br>
		@if ($forms->total()>0)
			{{ $forms->total() }} records found.
		@else
			No record found.
		@endif
 </div>
  <div class="col-md-6">
		<h1>Results</h1>
		<table class='table table-hover'>
		@if (!empty($results))
				@foreach($results as $result)
					<tr>
					<td>
							<a href='{{ URL::to('form/'. $result->form_code.'/'.$encounter_id) }}'>{{ $result->form_name }}</a>
					</td>
					<td>
						<div class='pull-right'>
								{{ DojoUtility::diffForHumans($formHelper->lastUpdate($patient->patient_id, $result->form_code)) }}
						</div>
					</td>
					<td width='10'>
							<a class='btn btn-primary btn-xs' href='{{ URL::to('form/'. $result->form_code.'/'.$patient->patient_id.'/create') }}'>
								<span class='glyphicon glyphicon-plus'></span>
							</a>
					</td>
					</tr>
				@endforeach
		@endif

		</table>
 </div>
</div>

@endsection
