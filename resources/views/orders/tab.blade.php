
<ul class="nav nav-tabs">
  <li @if ($plan=='laboratory') class="active" @endif><a href="plan?plan=laboratory">Laboratory</a></li>
  <li @if ($plan=='imaging') class="active" @endif><a href="plan?plan=imaging">Imaging</a></li>
<!--
  <li><a href="/imaging">Imaging (Pending)</a></li>
-->
  <li @if ($plan=='procedure') class="active" @endif><a href="procedure">Procedures</a></li>
  <li @if ($plan=='medication') class="active" @endif><a href="medication">Medications</a></li>
<!--
  <li @if ($plan=='fee_consultant') class="active" @endif><a href="plan?plan=fee_consultant">Fees</a></li>
-->
  <li @if ($plan=='discussion') class="active" @endif><a href="discussion">Discussion</a></li>
  <li @if ($plan=='order') class="active" @endif><a href="make">Orders</a></li>
</ul>
<br>
