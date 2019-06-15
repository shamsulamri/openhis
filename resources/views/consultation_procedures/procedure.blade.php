<div id="order_list"></div>
<div class="widget style1 gray-bg">
			<input type='text' class='form-control' placeholder="Enter procedure name" id='search' name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		{{ csrf_field() }}
		<div id="procedure_list"></div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
$(document).ready(function(){
			$(document).on('focusout', 'input', function(e) {
					var id = e.currentTarget.name;
					if (e.currentTarget.name != 'search') {
						updateProcedure(id);
					}
			});

			function updateProcedure(id) {
					id = id.split('_')[1];
					var price = $('#price_'.concat(id)).val();
					var discount = $('#discount_'.concat(id)).val();
					var markup = $('#markup_'.concat(id)).val();

					var dataString = parse('price=%s&discount=%s&markup=%s&order_id=%s', 
							price,
							discount,
							markup,
							id);

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('procedures.update') }}",
						data: dataString,
						success: function(){
							console.log('Procedure updated...');
						}
					});
			}

			function parse(str) {
					var args = [].slice.call(arguments, 1),
							i = 0;

					return str.replace(/%s/g, () => args[i++]);
			}

			$('#search').keyup(function(e){
				var value = $('#search').val();
				//if (e.which==13) {
						if (value.length >= 3) {
							var dataString = "search="+value+"&consultation_id={{ $consultation->consultation_id }}";

							console.log(value);
							$.ajax({
								type: "POST",
								headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
								url: "{{ route('procedures.find') }}",
								data: dataString,
								success: function(data){
									$('#procedure_list').html(data);
								}
							});
						} else {
							$('#procedure_list').html('');
						}
				//}
			});
});

function addItem(product_code) {
		var dataString = "product_code="+product_code+"&consultation_id={{ $consultation->consultation_id }}";

		$.ajax({
				type: "POST",
				headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
				url: "{{ route('procedures.add') }}",
				data: dataString,
				success: function(data){
						$('#order_list').html(data);
				}
		});

		$('#procedure_list').empty();
		$('#search').val('');
		//$("#search").focus();

}

function removeProcedure(order_id) {
		var dataString = "order_id="+order_id+"&consultation_id={{ $consultation->consultation_id }}";

		$.ajax({
		type: "POST",
				headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
				url: "{{ route('procedures.remove') }}",
				data: dataString,
				success: function(data){
						$('#order_list').html(data);
				}
		});
}

$.ajax({
type: "POST",
		headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
		url: "{{ route('procedures.list') }}",
		success: function(data){
				$('#order_list').html(data);
		}
});
</script>
