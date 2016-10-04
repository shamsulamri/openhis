<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\DojoUtility;
use App\User;

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
								'bill_group_by_user'=>'Bill Summary by User'
					],
					'encounters'=>['outpatient'=>'Outpatient', 'inpatient'=>'Inpatient'],
					'minYear'=>2011,
					'report'=>null,
					'today'=>DojoUtility::today(),
					'user' => $users,
				]);
		}

		public function generate(Request $request)
		{
				$server = \Config::get('host.report_server');
				$report = $request->report;
				$encounter = $request->encounter;
				$date_start = DojoUtility::dateWriteFormat($request->date_start)->toDateString();
				$date_end = DojoUtility::dateWriteFormat($request->date_end)->toDateString();
				$user = $request->user;

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
						default:
								return "QQQQ";
				}
		}
}
