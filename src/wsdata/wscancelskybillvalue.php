<?php

/***********
 * File: wscustomsvalue.php
 * 
 * Description: Build the object cancelSkybillValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;

class wscancelskybillvalue extends wsdata {

	// public $accountNumber
	// public $password
	public $language = 'fr_FR';
	// public $skybillNumber
	
	
	public function setaccountNumber ($value) {
		return $this->setVariableReg($this->accountNumber, $value, wsregex::__reg_AccountNumber);
	}
	public function setpassword ($value) {
		return $this->setVariableReg($this->password, $value, wsregex::__reg_Password);
	}
	public function setlanguage ($value) {
		return $this->setVariableReg($this->language, $value, wsregex::__reg_locale);
	}
	public function setskybillNumber ($value) {
		return $this->setVariableReg($this->skybillNumber, $value, wsregex::__reg_SkybillNumber);
	}
	public function RFLcheck() {
		if(isset($this->accountNumber, $this->password, $this->language, $this->skybillNumber)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " CancelSkybill object does not meet minimum requirements, please read the doc");
			return false;
		}
	}
}
?>