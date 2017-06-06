<div class="col-lg-12">
<h1>
<a href='{{ URL::to('form/results',[$encounter_id]) }}'>Forms</a> / {{ $form->form_name }}
</h1>
<a href='/chart/line/{{ $form->form_code }}/{{ $encounter_id }}' class='btn btn-primary'><span class='fa fa-line-chart'></span> Chart</a>
<a href='/form/{{ $form->form_code }}/{{ $encounter_id }}' class='btn btn-primary'><span class='fa fa-table'></span> Table</a>
<a href='/form/{{ $form->form_code }}/{{ $patient->patient_id }}/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
<br>
			<br>
			<div>
				<canvas id="{{ $chart_id }}" height="130"></canvas>
			</div>
			<div align='middle' id='{{ $chart_id }}_legend' class='chart-legend'></div>
</div>
<style>
.chart-legend li span{
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;
}
</style>
<script>
    var lineData = {
		labels: [
				@foreach($charts[0] as $data)
					"{{ $data['label'] }}",
				@endforeach
		],
        datasets: [
<?php $x=0; ?>
			@foreach($charts as $chart)
            {
                label: "{{ ucwords($keys[$x]) }}",
                strokeColor: "{{ $rgbs[$x] }}",
                pointColor: "{{ $rgbs[$x] }}",
                fillColor: "{{ $rgbs[$x] }}",
				pointHighlightFill: "#fff",
				data: [
						@foreach($chart as $data)
							"{{ $data['value'] }}",
						@endforeach
				]
			},
<?php $x++; ?>	
			@endforeach
		]
    };

	var lineOptions = {
		scaleShowGridLines: true,
		scaleGridLineColor: "rgba(0,0,0,.05)",
		scaleGridLineWidth: 1,
		scaleLabel: function(label){return  label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");},
		bezierCurve: false,
		bezierCurveTension: 0.4,
		pointDot: true,
		pointDotRadius: 4,
		pointDotStrokeWidth: 1,
		pointHitDetectionRadius: 20,
		datasetStroke: true,
		datasetStrokeWidth: 2,
		datasetFill: false,
		responsive: true,
		legend: {
				display: true,
				position: 'top',
				fullWidth: true,
				labels: {
						fontColor: 'rgb(255,99,132)'
						}
				}
	};


    var ctx = document.getElementById("{{ $chart_id }}").getContext("2d");
    var myChart = new Chart(ctx).Line(lineData, lineOptions);
	document.getElementById('{{ $chart_id }}_legend').innerHTML = myChart.generateLegend();
</script>

