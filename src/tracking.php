<?php

/***********
 * File: shipment.php
 * 
 * Description: Build the object shipment requested by WS
 *
 *
 ************/

namespace ladromelaboratoire\chronopostws;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;


class tracking {
	
	//All properties are set at need
	private $useExceptions;
	
	public function __construct($useExceptions = false) {
		$this->useExceptions = $useExceptions;
	}
/*	
	public function _cleanObject() {
		$vars = get_object_vars($this);
		foreach($vars as $key => $var) {
			unset($this->$key);
		}
	}
	public function newpod($array) {
		unset($this->podValue);
		$this->podValue = new wssearchpodvalue();
		$this->podValue->loadArray($array);
	}
	public function cancelSkybill($array) {
		unset($this->skybillvalue);
		$this->skybillValue = new wscancelskybillvalue();
		$this->skybillValue->loadArray($array);
	}
	public function newtracking($array) {
		unset($this->trackingValue);
		$this->trackingValue = new wstrackingoneparcelvalue();
		$this->trackingValue->loadArray($array);
	}
*/
}
?>