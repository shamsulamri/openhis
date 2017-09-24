<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CreditCard;
use Log;
use DB;
use Session;


class CreditCardController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$credit_cards = DB::table('credit_cards')
					->orderBy('card_name')
					->paginate($this->paginateValue);
			return view('credit_cards.index', [
					'credit_cards'=>$credit_cards
			]);
	}

	public function create()
	{
			$credit_card = new CreditCard();
			return view('credit_cards.create', [
					'credit_card' => $credit_card,
				
					]);
	}

	public function store(Request $request) 
	{
			$credit_card = new CreditCard();
			$valid = $credit_card->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$credit_card = new CreditCard($request->all());
					$credit_card->card_code = $request->card_code;
					$credit_card->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/credit_cards/id/'.$credit_card->card_code);
			} else {
					return redirect('/credit_cards/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$credit_card = CreditCard::findOrFail($id);
			return view('credit_cards.edit', [
					'credit_card'=>$credit_card,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$credit_card = CreditCard::findOrFail($id);
			$credit_card->fill($request->input());


			$valid = $credit_card->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$credit_card->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/credit_cards/id/'.$id);
			} else {
					return view('credit_cards.edit', [
							'credit_card'=>$credit_card,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$credit_card = CreditCard::findOrFail($id);
		return view('credit_cards.destroy', [
			'credit_card'=>$credit_card
			]);

	}
	public function destroy($id)
	{	
			CreditCard::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/credit_cards');
	}
	
	public function search(Request $request)
	{
			$credit_cards = DB::table('credit_cards')
					->where('card_name','like','%'.$request->search.'%')
					->orWhere('card_code', 'like','%'.$request->search.'%')
					->orderBy('card_name')
					->paginate($this->paginateValue);

			return view('credit_cards.index', [
					'credit_cards'=>$credit_cards,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$credit_cards = DB::table('credit_cards')
					->where('card_code','=',$id)
					->paginate($this->paginateValue);

			return view('credit_cards.index', [
					'credit_cards'=>$credit_cards
			]);
	}
}
