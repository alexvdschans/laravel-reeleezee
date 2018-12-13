<?php namespace AvdS\Reeleezee;

class ReeleezeeApiClient {

	protected $version = '1';
	protected $url = 'https://portal.reeleezee.nl/api/v1/';

	public function __construct(){
		$this->username 		= config('reeleezee.username');
		$this->password 		= config('reeleezee.password');
	}

	public function get($endpoint, $params = [])
	{

		if(isset($this->access_token)){
			$params['data']['access_token'] = $this->access_token;
		}
	
		$url 	=  $this->url . urlencode($endpoint);

		$headers = [
				'Content-Type: application/json'
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);
			
		return json_decode($server_output);

	}

	public function post($endpoint, $params = [])
	{

		$url =  $this->url . $endpoint;

		$headers = [
				'Content-Type: application/json'
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

		$server_output = curl_exec($ch);
		
		return true;
		
	}
	
	public function postDebug($endpoint, $params = [])
	{
	
		$url =  $this->url . $endpoint;
	
		$headers = [
				'Content-Type: application/json'
		];
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
	
		$server_output = curl_exec($ch);
		
	}
	
	public function put($endpoint, $params = [])
	{

		$url =  $this->url . $endpoint ;

		$headers = [
				'Content-Type: application/json'
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

		$server_output = curl_exec($ch);

		return true;

	}

	public function guid()
	{

		if (function_exists('com_create_guid') === true)
		{
			return trim(com_create_guid(), '{}');
		}

		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

	}
}