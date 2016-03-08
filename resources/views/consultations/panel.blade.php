<h2>
Consultation
</h2>
	<br>
    <div class='form-group'>
            <a class="btn btn-primary btn-lg" href="/consultations/close/{{ $consultation->consultation_id }}" role="button">Close Consultation</a>
    </div>
<br>
<ul class="nav nav-tabs nav-justified">
  <li role="presentation" class="
@if ($tab=='clinical')
	active
@endif
"><a href="/consultations/{{ $consultation->consultation_id }}/edit">Clinical</a></li>
  <li role="presentation" class="
@if ($tab=='diagnosis')
	active
@endif
"><a href="/consultation_diagnoses/{{ $consultation->consultation_id }}">Diagnosis</a></li>
  <li role="presentation" class="
@if ($tab=='order')
	active
@endif
"><a href="/orders/{{ $consultation->consultation_id }}" role="button">Order</a></li>
  <li role="presentation" class="
@if ($tab=='discharge')
	active
@endif
"><a href="/orders/{{ $consultation->consultation_id }}" role="button">Discharge</a></li>
</ul>
<br>
