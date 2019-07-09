@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id')
@endif

<br>
<table class='table'>
<thead>
	<tr>
<?php
	for ($i=1;$i<=3;$i++) {
?>
		<th><div align='center'>Day {{ $i }}</div></th>
<?php
	}
?>
	</tr>
</thead>
	<tr>
<?php
	for ($i=1;$i<=3;$i++) {
?>
		<td>
			@include('forms.content')
		</td>
<?php
	}
?>
	</tr>
</table>



@endsection
