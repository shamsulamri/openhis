<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Race;
use Log;
use DB;
use Session;
use Gate;
use App\Consultation;
use App\Encounter;

class TestController extends Controller
{
	public function consultation($id)
	{
			$encounter = Encounter::where('encounter_id', 509)->first();
			return $encounter;
			$consultation = Consultation::find($id);

			return $consultation->getEncounter;
	}

}
