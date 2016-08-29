<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Log;

class SpyController extends Controller
{
	public function getMethods($class_name)
	{
		$class_methods = get_class_methods(new PatientController());
		Log::info($class_name);

		foreach ($class_methods as $method_name) {
				Log::info($method_name);
		}
		return $class_methods;
	}	
}
