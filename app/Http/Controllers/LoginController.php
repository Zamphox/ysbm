<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use \GuzzleHttp\Client;
use \GuzzleHttp\Exception\RequestException;

class LoginController extends Controller
{
    //
	public function show(){
		return view('login');
	}

	public function login(Request $request){
		$validate = Validator::make($request->all(),[
    		'email'=>[
    				'email',
					'required',
    				],
    		'password'=>[
				'required',
				'regex:/^[a-zA-Z\p{N}!$?)(\-_@#$%^&*]+$/'
				]
    	]);

		if($validate->fails()){
    		return redirect()->back()->withInput()->withErrors($validate);
    	}

		$data = $request->all();

		$client = new \GuzzleHttp\Client();
		$headers = [
		  'Content-Type' => 'application/json'
		];

		$body = '{
			"email":"' . $data['email'] . '",
			"password":"' . $data['password'] . '"
			}';

		$request = new \GuzzleHttp\Psr7\Request('POST','https://api.shipments.test-y-sbm.com/login', $headers, $body);

		$res = $client->send($request, ['timeout' => 10, 'http_errors' => false]);
		$status_code = $res->getStatusCode();

		if($status_code == 200){
			$body = json_decode($res->getBody(), true);
			session(['bearer_token' => $body['data'][0]['token']]);
			return redirect('/');
		}else{
			$body = json_decode($res->getBody(), true);
			return redirect()->back()->with('error', $body['message']);
			}

	}

	public function logout(){
		session()->flush();
		return redirect('login');
	}
}