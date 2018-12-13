<?php namespace AvdS\Reeleezee;

use AvdS\Reeleezee\Resources\Products;
use AvdS\Reeleezee\Resources\SalesInvoices;
use AvdS\Reeleezee\Resources\Customers;

class Reeleezee {
	
	public function __construct()
	{
		$this->client 			= new ReeleezeeApiClient(); 
		$this->products 		= new Products($this->client);
		$this->sales_invoices 	= new SalesInvoices($this->client);
		$this->customers 		= new Customers($this->client);
		
	}
	
	public function products()
	{
		return $this->products->get();
	}
	
	public function salesInvoices()
	{
		return $this->sales_invoices;
	}
	
	public function customers()
	{
		return $this->customers;
	}
	
}