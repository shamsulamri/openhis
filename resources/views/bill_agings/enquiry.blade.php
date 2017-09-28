@extends('layouts.app')

@section('content')
<h1>Aging Enquiry

<a href="{{ url('/bill/aging') }}" class='btn btn-primary pull-right'><span class='glyphicon glyphicon-refresh'></span></a>
</h1>
<br>
<table class="table table-hover">
  <tr>
    <th></th>
    <th>0-30</th>
    <th>31-60</th>
    <th>61-90</th>
    <th>91-120</th>
    <th>>121</th>
    <th>Total</th>
    <th>%</th>
  </tr>
  <tr>
    <td>Patient</td>
	<td>	
		{{ $billHelper->agingPatient(1)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingPatient(1)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingPatient(2)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingPatient(2)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingPatient(3)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingPatient(3)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingPatient(4)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingPatient(4)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingPatient(5)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingPatient(5)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingPatient(5)[0]->total_amount?:"0" }}<br>
		100.0%
	</td>
	<td>	
		<strong>
		{{ number_format($billHelper->agingPatient(5)[0]->total_percentage,2) }}%
		</strong>
	</td>
  </tr>
  <tr>
    <td>Sponsor</td>
	<td>	
		{{ $billHelper->agingSponsor(1)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingSponsor(1)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingSponsor(2)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingSponsor(2)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingSponsor(3)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingSponsor(3)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingSponsor(4)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingSponsor(4)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingSponsor(5)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingSponsor(5)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingSponsor(5)[0]->total_amount?:"0" }}<br>
		100.0%
	</td>
	<td>	
		<strong>
		{{ number_format($billHelper->agingSponsor(5)[0]->total_percentage,2) }}%
		</strong>
	</td>
  </tr>
  <tr>
    <td>Total</td>
	<td>	
		{{ $billHelper->agingTotal(1)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingTotal(1)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingTotal(2)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingTotal(2)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingTotal(3)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingTotal(3)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingTotal(4)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingTotal(4)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingTotal(5)[0]->aging_amount?:"0" }}<br>
		{{ number_format($billHelper->agingTotal(5)[0]->percentage,2) }}%
	</td>
	<td>	
		{{ $billHelper->agingTotal(5)[0]->total_amount?:"0" }}<br>
		100.0%
	</td>
	<td>	
		<strong>
		{{ number_format($billHelper->agingTotal(5)[0]->total_percentage,2) }}%
		</strong>
	</td>
  </tr>
</table> 
@endsection
