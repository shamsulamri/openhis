<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Log;
use App\DojoUtility;

class SchemaController extends Controller
{
	public $paginateValue=10;

	public function schema($table) 
	{
			$fields = DB::select(DB::raw('DESCRIBE '.$table));
			return $fields;
	}

	public function index($table)
	{
			$records = DB::table($table)
					->paginate($this->paginateValue);

			return view('schema.index', [
					'records'=>$records,
					'table'=>$table,
			]);
	}

	public function form($table, $id = null)
	{
			$fields = DB::select(DB::raw('DESCRIBE '.$table));

			
			$menus = array();

			$references['race_code'] = 'ref_races,race_name';
			$references['gender_code'] = 'ref_genders,gender_name';
			$references['marital_code'] = 'ref_marital_statuses,marital_name';
			$references['flag_code'] = 'ref_patient_flags,flag_name';

			foreach ($fields as $field) {
					if ($this->endsWith($field->Field, "_code") == true) {
						if (empty($references[$field->Field])) {
							$ref_table = explode("_", $field->Field)[0];
							$references[$field->Field] = 'ref_'.$ref_table.'s,'.$ref_table.'_name';
						}
					}
			}

			foreach ($fields as $field) {
					if ($field->Key == 'MUL') {
						if (!empty($references[$field->Field])) {
								$ref_table = explode(",", $references[$field->Field])[0];
								$ref_name = explode(",", $references[$field->Field])[1];
								$keyvalues = DB::table($ref_table)->orderBy($ref_name)->pluck($ref_name, $field->Field);

								// Add empty value
								$keyvalues['']='';
								end($keyvalues);
								$last_key = key($keyvalues);
								$last_value = array_pop($keyvalues);
								$keyvalues = array_merge(array($last_key=>$last_value), $keyvalues);

								$menus[$field->Field] = $keyvalues;
						}
					}
			}

			$record = null;

			if ($id) {
					$record = DB::table($table)->where('id', '=', $id)->first();
			}


			return view('schema.form', [
					'fields'=>$fields,
					'table'=>$table,
					'menus'=>$menus,
					'record' => $record,
					'id'=>$id,
			]);
	}

	public function post(Request $request) 
	{
		$table = $request->table_name;
		$fields = DB::select(DB::raw('DESCRIBE '.$table));
		$skip = false;
		$record = array();

		foreach ($fields as $field) {

			$record_field = $field->Field;

			if ($record_field == 'PRI' and $field->Extra == 'auto_increment' ) $skip = true;

			if ($skip == false) {
					$record[$record_field] = $request[$record_field];
					if ($field->Type == 'date') {
							$date_value = $request[$record_field];
							if (DojoUtility::validateDate($date_value)==true) {
									$record[$record_field] = DojoUtility::dateWriteFormat($request[$record_field]);
							}
					}
			}


			$skip = false;
		}
				
		if (empty($request->id)) {
				$id = DB::table($table)->insertGetId($record);
		} else {
				$id = $request->id;
				DB::table($table)->where('id', $id)->update($record);
		}

		return redirect('/schema/index/'.$table);
	}

	public function delete($table, $id)
	{
		$record = DB::table($table)->where('id', '=', $id)->first();
		return view('schema.destroy', [
					'record'=>$record,
					'table'=>$table,
					'id'=>$id,
			]);
	}

	public function destroy(Request $request)
	{
		$record = DB::table($request->table)->where('id', '=', $request->id)->delete();
		return redirect('/schema/index/'.$request->table);
	}

	function endsWith($string, $endString) 
	{ 
		$len = strlen($endString); 
		if ($len == 0) { 
			return true; 
		} 
		if (substr($string, -$len) === $endString) {
				return true;
		} else {
				return false;
		}
	} 
	
}
