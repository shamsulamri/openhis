@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
	<br>
	@else
@endif
