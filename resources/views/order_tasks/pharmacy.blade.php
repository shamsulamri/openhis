@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>Order Task</h1>
<h3>{{ $location->location_name }}</h3>
@if (!empty($location->store))
<h5>{{ $location->store->store_name }}</h5>
@endif
@if ($order_tasks->count()>0)
<br>
<form action='/order_task/status' method='post'>
	<div class="row">
			<div class="col-xs-4">
					<button class="btn btn-primary" type="submit" value="Submit">Update Status</button>
					@can('module-order')
					<!--
					<a class='btn btn-primary' href='/orders/make'>Edit Orders</a>
					-->
					@endcan
			</div>
			<div align="right" class="col-xs-8">
@if ($encounter->encounter_code == 'inpatient')
					<a class="btn btn-primary" href="/medication_record/mar/{{ $encounter_id }}?view=1">View MAR</a>
@endif
@if (Auth::user()->author_id == 5)
					<a class="btn btn-primary" href="{{ Config::get('host.report_server') }}/ReportServlet?report=order_labels&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Lab Label</a>
@endif
@if (Auth::user()->author_id == 15)
					<a class="btn btn-primary" href="{{ Config::get('host.report_server') }}/ReportServlet?report=request_form&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Request Form</a>
@endif
@if (Auth::user()->author_id == 13 or Auth::user()->author_id = 18)
					<a class="btn btn-primary" href="{{ Config::get('host.report_server') }}/ReportServlet?report=drug_label&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Drug Label</a>
					<a class="btn btn-primary" href="{{ Config::get('host.report_server') }}/ReportServlet?report=drug_prescription&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Prescription</a>
					<a class="btn btn-primary" href="{{ Config::get('host.report_server') }}/ReportServlet?report=drug_discharge&id={{ $encounter->encounter_id }}" target="_blank" role="button"><span class='glyphicon glyphicon-print'></span>
 Discharge</a>
@endif
			</div>
	</div>
	<br>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">

<div class="tabs-container">
		<ul class="nav nav-tabs">
				<li class="active">
					<a data-toggle="tab" href="#tab-1">
						Orders
						@if (($count_ward)>0)
							 <label class="label label-warning">{{ $count_ward }}</label>
						@endif
					</a>
				</li>
				<li>
					<a data-toggle="tab" href="#tab-3">
						Discharge
						@if (($count_discharge)>0)
							 <label class="label label-warning">{{ $count_discharge }}</label>
						@endif
					</a>
				</li>
				<li>
					<a data-toggle="tab" href="#tab-2">
						Floor
						@if (($count_floor)>0)
							 <label class="label label-warning">{{ $count_floor }}</label>
						@endif
					</a>
				</li>
				<li>
					<a data-toggle="tab" href="#tab-4">
						Completed 
						@if (($count_completed)>0)
							 <label class="label label-warning">{{ $count_completed }}</label>
						@endif
					</a>
				</li>
				<li>
					<a data-toggle="tab" href="#tab-5">
						Stop 
						@if (($count_stop)>0)
							 <label class="label label-warning">{{ $count_stop }}</label>
						@endif
					</a>
				</li>
		</ul>
		<div class="tab-content">
			<div id="tab-1" class="tab-pane active">
				<div class="panel-body">
						@include('order_tasks.ward')
				</div>
			</div>
			<div id="tab-2" class="tab-pane">
				<div class="panel-body">
						@include('order_tasks.floor')
				</div>
			</div>
			<div id="tab-3" class="tab-pane">
				<div class="panel-body">
						@include('order_tasks.discharge')
				</div>
			</div>
			<div id="tab-4" class="tab-pane">
				<div class="panel-body">
						@include('order_tasks.completed')
				</div>
			</div>
			<div id="tab-5" class="tab-pane">
				<div class="panel-body">
						@include('order_tasks.stop')
				</div>
			</div>
		</div>
</div>
	{{ Form::hidden('ids', $ids) }}
	{{ Form::hidden('dispense_ids', $dispense_ids) }}
	{{ Form::hidden('encounter_id', $encounter_id) }}
	<br>
	<button class="btn btn-primary" type="submit" value="Submit">Update Status</button>
</form>
@endif
@if (isset($search)) 
	{{ $order_tasks->appends(['search'=>$search])->render() }}
	@else
	{{ $order_tasks->render() }}
@endif
<br>
@if ($order_tasks->total()>0)
	{{ $order_tasks->total() }} records found.
@else
	<h3>
	No orders found.
	</h3>
@endif

<script>
$(document).ready(function(){
  $("input").keypress(function(e){
	var id = e.currentTarget.id;
	//document.getElementById(id).checked = true;
	$('#'+id).iCheck('check');
  });
});

</script>
@endsection
