<h4>Seen by {{ $consultation->user->name }}</h4>
<h5>{{ date('d F, H:i', strtotime($consultation->created_at)) }}</h5>
<h5>{{ $consultation->queue->location->location_name }}</h5>
<br>
