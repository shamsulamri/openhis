@extends('layouts.app')

@section('content')
@include('consultations.panel')
<style>

.small-font {
	font-size: 12px;
}

.table > tbody > tr:first-child > td {
    border: none;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
	}

.dropdown-submenu>a:after {
		display: block;
		content: " ";
		float: right;
		width: 0;
		height: 0;
		border-color: transparent;
		border-style: solid;
		border-width: 5px 0 5px 5px;
		border-left-color: #ccc;
		margin-top: 5px;
		margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}

label {
  display: block;
  padding-top: 5px;
  text-indent: -15px;
  font-size: 12px;
  font-weight: normal;
}
</style>
<h1>Medications</h1>
<div id="drugHistory"></div>
<h3>Orders</h3>
<div class="widget style1 gray-bg">
	<div id="medicationList"></div>
	<input type='text' class='form-control' placeholder="Enter medication name" id='search' name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
{{ csrf_field() }}
<div id="drugList"></div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
$(document).ready(function(){


			$(document).on('focusout', 'input', function(e) {
					var id = e.currentTarget.name;
					if (e.currentTarget.name != 'search') {
						updateDrug(id);
					}
			});

			$(document).on('focusout', 'select', function(e) {
					var id = e.currentTarget.name;
					if (e.currentTarget.name != 'search') {
						updateDrug(id);
					}
			});


			function updateDrug(id) {
					var dosage = $('#dosage_'.concat(id)).val();
					var frequency = $('#frequency_'.concat(id)).find('option:selected').val();
					var duration = $('#duration_'.concat(id)).val();
					var period = $('#period_'.concat(id)).find('option:selected').val();
					console.log(dosage,frequency, duration, period);

					var dataString = parse('drug_dosage=%s&frequency_code=%s&drug_duration=%s&period_code=%s&order_id=%s', dosage, frequency, duration, period, id);

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.update') }}",
						data: dataString,
						success: function(){
							console.log('drug updated...');
						}
					});
			}

			$('#search').keyup(function(e){
				var value = $('#search').val();
				//if (e.which==13) {
				if (value.length >= 3) {
					var dataString = "search="+value+"&consultation_id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.find') }}",
						data: dataString,
						success: function(data){
							$('#drugList').html(data);
						}
					});
					//$('#search').val('');
				} else {
					$('#drugList').html('');
				}
			});

			function addDrug2(id) {
					var dataString = "id="+id+"&consultation_id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.add') }}",
						data: dataString,
						success: function(data){
							$('#medicationList').html(data);
						}
					});

					$('#drugList').empty();
					$('#search').val('');
					$("#search").focus();
			}


			$(document).on('keydown', function ( e ) {
					// You may replace `c` with whatever key you want
					if (e.key == "Escape") {
							$("#search").focus();
					}
			});

			$('#drugList').empty();
			$('#search').val('');
			$("#search").focus();

			$.ajax({
					type: "POST",
					headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
					url: "{{ route('medications.table') }}",
					success: function(data){
							$('#medicationList').html(data);
					}
			});

			$.ajax({
					type: "POST",
					headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
					url: "{{ route('medications.history') }}",
					success: function(data){
							$('#drugHistory').html(data);
					}
			});
});

			function showHistory() {
					$.ajax({
							type: "POST",
							headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
							url: "{{ route('medications.history') }}",
							success: function(data){
									$('#drugList').html(data);
							}
					});
			}

			function parse(str) {
					var args = [].slice.call(arguments, 1),
							i = 0;

					return str.replace(/%s/g, () => args[i++]);
			}

			function addDrug(drug_code) {
					var dataString = "drug_code="+drug_code+"&consultation_id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.add') }}",
						data: dataString,
						success: function(data){
							$('#medicationList').html(data);
						}
					});

					$('#drugList').empty();
					$('#search').val('');
					$("#search").focus();
			}

			function removeDrug(order_id) {
					var dataString = "order_id="+order_id+"&consultation_id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.remove') }}",
						data: dataString,
						success: function(data){
							$('#medicationList').html(data);
						}
					});
			}

			function renewDrug(order_id) {
					var dataString = "order_id="+order_id+"&consultation_id={{ $consultation->consultation_id }}";

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('medications.renew') }}",
						data: dataString,
						success: function(data){
							$('#medicationList').html(data);
						}
					});
			}
</script>
@endsection
