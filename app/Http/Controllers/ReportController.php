<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\DojoUtility;
use App\User;
use App\Ward;
use App\AppointmentService;

class ReportController extends Controller
{
		public function index()
		{
			$users = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
					->orderBy('name')
					->lists('name','username')
					->prepend('','');

				return view('reports.index',[
						'reports'=>[
								'discharge'=>'Discharge', 
								'bill_list'=>'Bills', 
								'bill_group_by_user'=>'Bill Summary by User',
								'product_limit'=>'Product Reorder Limit',
								'loan_request'=>'Loan Request', 
								'loan_distribution'=>'Loan Distribution', 
								'folder_request'=>'Folder Request', 
								'folder_distribution'=>'Folder Distribution', 
								'preadmission'=>'Preadmission', 
								'appointment'=>'Appointment', 
					],
					'encounters'=>['outpatient'=>'Outpatient', 'inpatient'=>'Inpatient'],
					'minYear'=>2011,
					'report'=>null,
					'today'=>DojoUtility::today(),
					'tomorrow'=>DojoUtility::tomorrow(),
					'user' => $users,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
				]);
		}

		public function generate(Request $request)
		{
				$server = \Config::get('host.report_server');
				$report = $request->report;
				$encounter = $request->encounter;
				$ward_code = $request->ward_code;
				$date_start = DojoUtility::dateWriteFormat($request->date_start)->toDateString();
				$date_end = DojoUtility::dateWriteFormat($request->date_end)->toDateString();
				$user = $request->user;
				$service_id = $request->service_id;

				switch ($request->report) {
						case "discharge":
								$url = sprintf("%s/ReportServlet?report=%s&encounterType=%s&dateStart=%s&dateEnd=%s&reportUser=%s",
											$server,
											$report,
											$encounter,
											$date_start,
											$date_end,
											$user
									);
								return redirect($url);
								break;
						case "bill_list":
								$url = sprintf("%s/ReportServlet?report=%s&dateReport=%s&sponsorCode=%s&reportUser=%s",
											$server,
											$report,
											$date_start,
											'',
											$user
									);
								return redirect($url);
								break;
						case "bill_group_by_user":
								$url = sprintf("%s/ReportServlet?report=%s&dateReport=%s",
											$server,
											$report,
											$date_start,
											'',
											$user
									);
								return redirect($url);
								break;
						case 'preadmission':
								$url = sprintf("%s/ReportServlet?report=%s&dateStart=%s&dateEnd=%s&wardCode=%s",
											$server,
											$report,
											$date_start,
											$date_end, 
											$ward_code
									);
								return redirect($url);
								break;
						case 'appointment':
								$url = sprintf("%s/ReportServlet?report=%s&dateStart=%s&dateEnd=%s&serviceId=%s",
											$server,
											$report,
											$date_start,
											$date_end, 
											$service_id
									);
								return redirect($url);
								break;
						default:
								$url = sprintf("%s/ReportServlet?report=%s&dateStart=%s&dateEnd=%s",
											$server,
											$report,
											$date_start,
											$date_end
									);
								return redirect($url);
				}
		}
}
