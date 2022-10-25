<?php

/***********
 * File: ws-skybillparamsvalue.php
 * 
 * Description: Build the object skybillparamsvalue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;

class wsskybillparamsvalue extends wsdata {

	//only mandatory properties are set by default
	//optional can be added by calling setters
	// public $mode = 'PDF';
	//public $duplicata = 'N';
	//public $withReservation = '0';
	
	public function setmode ($mode) {
		return $this->setVariableReg($this->mode, $mode, wsregex::__reg_SkybillMode);
	}
	public function setduplicata ($duplicata) {
		return $this->setVariableReg($this->duplicata, $duplicata, wsregex::__reg_YesNo);
	}
	public function setwithReservation ($resa) {
		return $this->setVariableReg($this->withReservation, $resa, wsregex::__reg_012);
	}
	public function loadArray($array) {
		parent::loadArray($array);
		if ($this->countModes() > 1) $this->forceReservation();
	}
	public function getModesArray() {
		return explode("|", $this->mode);
	}
	public function countModes() {
		return count($this->getModesArray());
	}
	public function forceReservation() {
		$this->setwithReservation("1");
	}
	public function RFLcheck() {
		if( isset($this->mode)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " SkybillParamsValue does not meet minimum requirement");
			return false;
		}
	}
}
?>