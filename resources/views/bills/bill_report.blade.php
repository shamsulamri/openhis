@extends('layouts.app')

@section('content')
<h1>PDF Report</h1>
<br>
<form id='form' action='/bill/report' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-12">
					<div class='form-group'>
						<label class='col-sm-1 control-label'><div align='left'>Report</div></label>
						<div class='col-sm-7'>
							{{ Form::select('report_code', $reports ,null, ['id'=>'report_code','class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_start' class='col-sm-3 control-label'><div align='left'>From</div></label>
						<div class='col-sm-9'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="date_start" id="date_start" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($date_start) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'>To</label>
						<div class='col-sm-9'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="date_end" id="date_end" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($date_end) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Encounter</div></label>
						<div class='col-sm-9'>
							{{ Form::select('encounter_code', $encounter_type,$encounter_code, ['id'=>'encounter_code','class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Type</div></label>
						<div class='col-sm-9'>
							{{ Form::select('type_code', $patient_types,$type_code, ['id'=>'type_code', 'class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-12">
					<div class='form-group'>
						<label class='col-sm-1 control-label'><div align='left'>Consultants</div></label>
						<div class='col-sm-7'>
            				{{ Form::select('user_id', $consultants,null, ['id'=>'user_id','class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-12">
					<div class='form-group'>
						<label class='col-sm-1 control-label'><div align='left'>Sponsor</div></label>
						<div class='col-sm-7'>
							{{ Form::select('sponsor_code', $sponsors,$sponsor_code, ['id'=>'sponsor_code','class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label  class='col-sm-3 control-label'><div align='left'>Shift</div></label>
						<div class='col-sm-9'>
							{{ Form::select('shift_code', array(''=>'','shift_1' => '0700 - 1400', 'shift_2' => '1400 - 2100', 'shift_3'=>'2100 - 0700'),null, ['class'=>'form-control', 'id'=>'shift_code']) }}
						</div>
					</div>
			</div>
	</div>

	<br>
	<a href='#' onclick='javascript:bill_report();' class='btn btn-primary'>View Report</a>
	<input type='hidden' id='export_report' name="export_report">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
</tbody>
</table>

<h3>Required Parameters</h3>
<br>
<h5>Bill Report</h5>
- From, To, Encounter, Type
<br>
<br>
<h5>Panel Report</h5>
- From, To, Sponsor
<br>
<br>
<h5>Consultant Report By User</h5>
- From, To, Consultant
<br>
<br>
<h5>Consultant Report By Product</h5>
- From, To 
<br>
<br>
<h5>Shift Report</h5>
- From, Encounter, Type, Shift
<script>
		$('#date_start').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('#date_end').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		function bill_report() {
				from = $("#date_start").val().split("/");
				dateStart = "";
				dateStart = dateStart.concat(from[2],"/", from[1],"/", from[0]);

				dateEnd = "";
				if ($("#date_end").val()) {
						to = $("#date_end").val().split("/");
						dateEnd = dateEnd.concat(to[2],"/", to[1],"/", to[0]);
				}

				report = $("#report_code").val();
				url = "{{ Config::get('host.report_server') }}/ReportServlet?report="+report;
				url = url.concat("&dateStart=", dateStart);
				url = url.concat("&dateEnd=", dateEnd);
				url = url.concat("&encounterType=", $("#encounter_code").val());
				url = url.concat("&typeCode=", $("#type_code").val());
				url = url.concat("&sponsorCode=", $("#sponsor_code").val());
				url = url.concat("&shiftCode=", $("#shift_code").val());
				url = url.concat("&user_id=", $("#user_id").val());

				var win = window.open(url, '_blank');
		}
</script>
</script>
@endsection
