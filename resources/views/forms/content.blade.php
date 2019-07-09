
<table class="table table-hover">
 <thead>
	<tr>	
		<th>Time</th>
@foreach($properties as $property)
		<th>{{ $property->property->property_name }}</th>
@endforeach
	</tr>
</thead>
<?php
	for ($i=0;$i<24;$i++) {
?>
	<tr>
		<td>{{ $i }}</td>
		@foreach($properties as $property)
		<td></td>
		@endforeach
	</tr>

<?php
	}
?>
</table>
