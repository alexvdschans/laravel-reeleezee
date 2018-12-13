<?php namespace AvdS\Reeleezee\Resources;

use AvdS\Reeleezee\Reeleezee;
use AvdS\Reeleezee\ReeleezeeApiClient;

class Customers {
	
	public $data;
	public $resource = 'Customers';
	
	public function __construct(ReeleezeeApiClient $api)
	{
	
		$this->api = $api;
	
	}
	
	public function __get($attribute)
	{
		return $this->data->{$attribute};
	}
	
	public function all()
	{
		
		$data = $this->api->get();
		
		return $data;
		
	}
	
	public function get($guid)
	{
		
		$resource = $this->resource . '/' . $guid;
	
		$this->data = $this->api->get($resource);
	
		return $this;
		
	}
	
	public function create(array $data = [])
	{
		
		$guid = $this->api->guid();
		
		$params = [
			"EntityKind" => 1,
			"Name" 			=> $data['name'],
			"SearchName" 	=> $data['name'],
			"id" => $guid,
			"CommunicationChannelList" => [
				[
					"id" => $this->api->guid(),
					"CommunicationType" => 10,
					"FormattedValue" => $data['email']							
				]
			]
		];
		
		$resource = $this->resource . '/' . $guid;
		
		$this->api->put($resource, $params);
	
		if($data['vatNumber'] !== ""){
			
			$vatUpdate = [
					'IdentificationNumber' => $data['vatNumber'],
					'Type' => 1
			];
			
			$this->api->put($resource . '/FiscalProcessParameters/' . $this->api->guid(), $vatUpdate);
			
		}
		
		if(isset($data['address'])){			
			$this->api->put($resource . '/Addresses/' . $this->api->guid(), $data['address']);
		};
		
		return $this->get($guid);
		
	}
	
	public function update($id, array $data = [])
	{
		
		$guid = $id;
		
		$params = [
				"EntityKind" => 1,
				"Name" 			=> $data['name'],
				"SearchName" 	=> $data['name'],
				"id" => $guid,
		];
		
		$resource = $this->resource . '/' . $guid;
		
		$this->api->put($resource, $params);
		
		if($data['vatNumber'] !== ""){
				
			$vatUpdate = [
					'IdentificationNumber' => $data['vatNumber'],
					'Type' => 1
			];
				
			$this->api->put($resource . '/FiscalProcessParameters/' . $this->api->guid(), $vatUpdate);
				
		}
		
		if(isset($data['address'])){
			$this->api->put($resource . '/Addresses/' . $this->api->guid(), $data['address']);
		};
		
		return $this->get($guid);
		
	}
	
	public function find($field, $query)
	{
		
		$resource = $this->resource . "?\$filter=startswith($field,'$query')";
		
		$result = $this->api->get($resource);
		
		return $result->value;
		
	}
	

}

