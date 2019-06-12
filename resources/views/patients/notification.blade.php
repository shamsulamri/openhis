<?php
	$notifyFlag = false;
?>

@if ($patient->patient_block==1)
<?php $notifyFlag = true; ?>
	<div class='alert alert-danger'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Danger !</strong> Account blocked by management.
	</div>
@endif

@if ($patient->outstandingBill() < 0) 
<?php $notifyFlag = true; ?>
	<div class="alert alert-warning">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Warning !</strong> Outstanding bill.
	</div>
@endif

@if (!empty($encounter->discharge))
		@if (!$encounter->encounterPaid())
<?php $notifyFlag = true; ?>
			<div class='alert alert-warning'>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>Warning !</strong> Encounter not paid.
			</div>
		@endif
@endif

@if (count($patient->getDischargeOrders()) > 0) 
<?php $notifyFlag = true; ?>
	<div class='alert alert-warning' role='alert'>
		<strong>Warning !</strong> Incomplete discharge orders.
	</div>
@endif


