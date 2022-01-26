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

class wssearchpodvalue extends wsdata {

	//only mandatory properties are set by default
	//optional can be added by calling setters
	// accountNumber
	// password
	public $language = 'fr_FR';
	public $pdf = true;
	// skybillNumber
	// sendersRef
	
	public function setaccountNumber ($value) {
		return $this->setVariableReg($this->accountNumber, $value, wsregex::__reg_AccountNumber);
	}
	public function setlanguage ($value) {
		return $this->setVariableReg($this->language, $value, wsregex::__reg_locale);
	}
	public function setpassword ($value) {
		$this->password = $value;
		return true;
	}
	public function setpdf ($value) {
		return $this->setVariableBool($this->pdf, $value);
	}
	public function setskybillNumber($value) {
		return $this->setVariableReg($this->skybillNumber, $value, wsregex::__reg_SkybillNumber);
	}
	public function setsendersRef($value) {
		return $this->setVariableReg($this->sendersRef, $value, wsregex::__reg_Refs);
	}
	public function RFLcheck() {
		if( isset($this->accountNumber, $this->language, $this->password, $this->pdf, $this->skybillNumber)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " Searchpodvalue dataset does not meet minimum requirements");
			return false;
		}
	}
}
?>