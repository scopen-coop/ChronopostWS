<?php

/***********
 * File: shipment.php
 * 
 * Description: Build the object quickcost requested by WS
 *
 *
 ************/

namespace ladromelaboratoire\chronopostws;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;
// use ladromelaboratoire\chronopostws\wsdata\wsquickcostvalue;



class quickcost {
	
	private $useExceptions;
	
	public function __construct($useExceptions = false) {
		$this->useExceptions = $useExceptions;
	}

	public function setquickcostValue($array) {
		unset($this->quickcostValue);
		// $this->quickcostValue = new wsquickcostvalue($this->useExceptions);
		// $this->quickcostValue->loadArray($array);
	}

	public function RFLcheck() {
		return true;
	}
	
	public function __destruct() {
	}
}
?>