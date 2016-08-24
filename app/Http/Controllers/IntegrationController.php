<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Log;

class IntegrationController extends Controller
{
		public function test()
		{
					$employees = DB::connection('mysql2')->select("select * from siso_emp_his");
					foreach ($employees as $employee) {
							Log::info($employee->nickname);
					}
							
					return "X";
		}
	
}
