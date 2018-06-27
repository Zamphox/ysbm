<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Validator;

class IndexController extends Controller
{
    //
	function paginateCollection($items, $perPage, $page = null, $options = [])
	{
		$page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
		$items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
		return new \Illuminate\Pagination\LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
	}

	public function show(Request $request){

		$client = new \GuzzleHttp\Client();
		$headers = [
			'Authorization' => 'Bearer ' . $request->session()->get('bearer_token') . '',
			'Content-Type' => 'application/json'
		];

		$request = new \GuzzleHttp\Psr7\Request('GET','https://api.shipments.test-y-sbm.com/shipment', $headers);
		$res = $client->send($request, ['timeout' => 10, 'http_errors' => false]);
		$status_code = $res->getStatusCode();

		if($status_code == 200){
			$data = json_decode($res->getBody(), true);
			$collection = collect($data['data']['shipments']);

			return view('index')->with('data', $this->paginateCollection($collection, 6));

		}else{
			$body = json_decode($res->getBody(), true);
			return redirect('login')->with('error', $body['error']);
			}


	}

	public function newShipment(Request $request){

		$validate = Validator::make($request->all(),[

    		'name'=>[
				'required',
				'max:40',
				'regex:/^[a-zA-Z\p{N}!$?)(\-_@#$%^ \/&*]+$/'
				]
    	]);

		if($validate->fails()){
    		return redirect()->back()->withInput()->withErrors($validate);
    	}

		$data = $request->all();
		$client = new \GuzzleHttp\Client();
		$headers = [
			'Authorization' => 'Bearer ' . $request->session()->get('bearer_token') . '',
			'Content-Type' => 'application/json'
		];
		$body = '{
			"id":"' . substr(time(), -5) . '",
			"name":"' . $data['name'] . '"
		}';

		$request = new \GuzzleHttp\Psr7\Request('POST','https://api.shipments.test-y-sbm.com/shipment', $headers, $body);
		$res = $client->send($request, ['timeout' => 10, 'http_errors' => false]);
		$status_code = $res->getStatusCode();

		if($status_code == 200){
			return redirect()->back()->with('success', 'Shipment created');
		}else{
			$body = json_decode($res->getBody(), true);
			return redirect()->back()->with('error', $body['error']);
			}
	}
}