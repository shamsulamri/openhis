<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use DB;
use Session;
use Gate;
use App\Patient;
use App\MedicalAlert;
use App\PatientDependant;
use Carbon\Carbon;
use mikehaertl\pdftk\Pdf;
use mikehaertl\pdftk\FdfFile;
use App\DojoUtility;
use App\Encounter;
use App\Admission;
use App\EncounterHelper;

class PDFController extends Controller
{

	public function encounter($patient_id, $form_name='darah.pdf')
	{

			$encounter_helper = new EncounterHelper();
			$encounter = $encounter_helper->getActiveEncounter($patient_id);

			if (!$encounter) {
					return "No active encounter";
			}

			$admission = $encounter_helper->getCurrentAdmission($encounter->encounter_id);
			$patient = $encounter->patient;

			$pdf = new Pdf('forms/'.$form_name.'.pdf');
			$consultant = "";
			if ($admission) {
				$consultant = $admission->consultant->name;
			}

			$pdf->fillForm([
					        'name'=>$patient->patient_name,
					        'identification'=>$patient->patient_new_ic,
							'today'=>DojoUtility::today(),
							'age'=>strtoupper($patient->patientAgeNumber()),
							'gender'=>strtoupper($patient->gender->gender_name),
							'consultant'=>strtoupper($consultant),
							'address'=>strtoupper($patient->getCurrentAddress()),
						])
						->needAppearances();

			if (!$pdf->saveAs('forms/'.$patient->patient_id.'.pdf')) {
				$error = $pdf->getError();
				return $error;
			}

			return redirect('forms/'.$patient->patient_id.'.pdf');
	}

}
