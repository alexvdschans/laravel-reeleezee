<?php namespace AvdS\Reeleezee\Resources;

use AvdS\Reeleezee\Reeleezee;
use AvdS\Reeleezee\ReeleezeeApiClient;

class Products {
	
	public function __construct(ReeleezeeApiClient $api)
	{
		
		$this->api = $api;
		
	}
	
	public function all()
	{
	
		$data = $this->api->get('Products');
	
		return $data;
	
	}
	
	public function create()
	{
	
	}
	
	
}

