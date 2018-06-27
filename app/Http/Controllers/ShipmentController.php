<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class ShipmentController extends Controller
{
    //

	public function show(Request $request){

		return view('shipment');

	}

	public function add(Request $request,$id){

		$validate = Validator::make($request->all(),[
    		'name'=>[

    				]
    	]);

		if($validate->fails()){
    		return redirect()->back()->withInput()->withErrors($validate);
    	}

		$data = $request->all();
		$product_list = [

		];

		$body = '{
			"id":"' . rand(100000, 999999) . '",
			"shipment_id":"' . $data['shipment_id'] . '",
			"name":"' . $product_list[rand(0,count($product_list))] . ' ' . substr(md5(microtime()),rand(0,26),6) . '",
			"code":"' . rand(100000, 999999) . '"
			}';

		$client = new \GuzzleHttp\Client();
		$headers = [
			'Authorization' => 'Bearer ' . $request->session()->get('bearer_token') . '',
			'Content-Type' => 'application/json'
		];

		$request = new \GuzzleHttp\Psr7\Request('POST','https://api.shipments.test-y-sbm.com/item', $headers, $body);
		$res = $client->send($request, ['timeout' => 10, 'http_errors' => false]);
		$status_code = $res->getStatusCode();

		if($status_code == 200){
			return redirect()->back();

		}else{
			$body = json_decode($res->getBody(), true);
			return redirect('login')->with('error', $body['message']);
			}
	}

	public function delete(Request $request, $id){
		$client = new \GuzzleHttp\Client();
		$headers = [
			'Authorization' => 'Bearer ' . $request->session()->get('bearer_token') . '',
			'Content-Type' => 'application/json'
		];

		$request = new \GuzzleHttp\Psr7\Request('DELETE','https://api.shipments.test-y-sbm.com//shipment/' . $id, $headers);
		$res = $client->send($request, ['timeout' => 10, 'http_errors' => false]);
		$status_code = $res->getStatusCode();

		return redirect('/')->with('success', 'Shipment deleted');

	}

	public function shareToken(Request $request){
		return $request->session()->get('bearer_token');
	}

}