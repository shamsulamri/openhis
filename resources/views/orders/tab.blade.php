
<ul class="nav nav-tabs">
  <li @if ($plan=='order') class="active" @endif><a href="/orders/make">Orders</a></li>
  <li @if ($plan=='laboratory') class="active" @endif><a href="/orders/plan?plan=laboratory">Laboratory</a></li>
<!--
  <li @if ($plan=='imaging') class="active" @endif><a href="/orders/plan?plan=imaging">Imaging</a></li>
-->
  <li @if ($plan=='imaging2') class="active" @endif><a href="/imaging?plan=imaging2">Imaging</a></li>
  <li @if ($plan=='procedure') class="active" @endif><a href="/orders/procedure">Procedures</a></li>
  <li @if ($plan=='medication') class="active" @endif><a href="/orders/medication">Medications</a></li>
  <li @if ($plan=='fee_consultant') class="active" @endif><a href="/orders/plan?plan=fee_consultant">Consultation Fees</a></li>
<!--
  <li @if ($plan=='discussion') class="active" @endif><a href="/orders/discussion">Discussion</a></li>
-->
</ul>
<br>
