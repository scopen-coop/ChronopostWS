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
use ladromelaboratoire\chronopostws\wsdata\wscancelskybillvalue;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;


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
*/
	public function setcancelSkybillValue($array) {
		unset($this->cancelSkybillValue);
		$this->cancelSkybillValue = new wscancelskybillvalue($this->useExceptions);
		$this->cancelSkybillValue->loadArray($array);
	}
/*
	public function newtracking($array) {
		unset($this->trackingValue);
		$this->trackingValue = new wstrackingoneparcelvalue();
		$this->trackingValue->loadArray($array);
	}
*/
	public function RFLcheck() {
		if (!$this->cancelSkybillValue->RFLcheck()) {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " cancelSkybillValue dataset not valid for WS");
			return false;
		}
		return true;
	}
}
?>