<?php namespace AvdS\Reeleezee\Resources;

use AvdS\Reeleezee\Reeleezee;
use AvdS\Reeleezee\ReeleezeeApiClient;
use Carbon\Carbon;

class SalesInvoices {
	
	public $data;
	public $resource = 'SalesInvoices';
	
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

		$date = Carbon::createFromFormat('Y-m-d', $data['date']);
		$dueDays = 30;
		
		$params = [
			"Date" 			=>  $data['date'] . "T00:00:00",
			"Header" 		=> "Factuur " . $data['invoiceNumber'],
			"Status" 		=> 3,
			"DocumentType" 	=> 10,
			"Type" 			=> 1,
			"Origin"		=> 2,
			"InvoiceNumber" => $data['invoiceNumber'],
			"DueDate" 		=> $date->addDays($dueDays)->format('Y-m-d') . "T00:00:00",
			"Entity" => [					
				"id" => $data['customerId']
			],
		];
				
		foreach ($data['products'] as $product){
			
			if(!isset($params['DocumentLineList'])){				
				$params['DocumentLineList'] = [];
			}
			
			$line = [
					'Quantity' 				=> 1,
					'Price' 				=> $product['price'],
					'Description' 			=> $product['description'],
// 					'InvoiceLineType'		=> 4,
					'TaxRate' 				=> [
							'id' => $this->getVatId($product['taxPct'])
					]
			];
			
			if(isset($product['ledger'])){
				
				$line['DocumentCategoryAccount'] = [
						'id' => $this->getDocumentCategory($product['ledger'])
				];
				
			}
			
			$params['DocumentLineList'][] = $line;
		}
		
		$guid = $this->api->guid();
		
		$resource = $this->resource . '/' . $guid;
		
		$this->api->put($resource, $params);
		
		return $this->get($guid);
		
	}
	
	public function update(array $data = [])
	{
	
		$params = [
			"id" => $this->id,
		];	
		
		if(isset($data['customer'])){
			$params["Entity"] = [
				"id" => $data['customer']['id']
			];
		}
		
		$id 		= $this->id;
		$resource 	= $this->resource . '/' . $id;
		
		$this->api->put($resource, $params);
		
		return $this->get($id);
		
	}
	
	public function book()
	{
		
		$params = [
			"Type" => 17
		];
		
		$resource 	= $this->resource . '/' . $this->id . '/Actions';
		
		$this->api->post($resource, $params);
		
		return true;
		
	}
	
	public function getVatId($vatPercentage)
	{
		
		$vatIds = [
				21 => '1e44993a-15f6-419f-87e5-3e31ac3d9383', // NL - Hoog
				6  => '3a44e504-aceb-4ea9-a9a4-aa8b689676df', // NL - Laag
				0  => 'e984f42c-de46-4408-84a4-24965a64029a' // EU - Hoog (0%)
		];
		
		return $vatIds[$vatPercentage];
		
	}

	public function getDocumentCategory($ledger)
	{	
		
		$resource = 'DocumentCategoryAccounts?$expand=*&$filter=Account/AccountNumber eq \''. $ledger . '\' and DocumentCategory/DocumentType eq \'10\'';
		
		$ledger = $this->api->get($resource);
		
		if(isset($ledger->value[0])){

			$id = $ledger->value[0]->id;
			
			return $id;
			
			
		} else {
			
			return 'bdca0ebf-cfb3-427c-bc69-18ee11b544d5';
			
		}		
		
	}
	
}

