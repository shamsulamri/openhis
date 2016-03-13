<h2>{{ $consultation->encounter->patient->patient_name }}</h2>
<h4>Seen by {{ $consultation->user->name }}</h4>
<h4>{{ date('d F, H:i', strtotime($consultation->created_at)) }}</h4>
<br>
