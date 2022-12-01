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

class wstrackingoneparcelvalue extends wsdata {

	//only mandatory properties are set by default
	//optional can be added by calling setters
	public $language = 'fr_FR';
	// skybillNumber	
	
	public function setskybillNumber ($value) {
		return $this->setVariableReg($this->skybillNumber, $value, wsregex::__reg_SkybillNumber);
	}
	public function setlanguage ($value) {
		return $this->setVariableReg($this->language, $value, wsregex::__reg_locale);
	}
	public function RFLcheck() {
		if( isset($this->skybillNumber, $this->language)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " To track parcel, we need skybill number and language to be set");
			return false;
		}
	}
}
?>