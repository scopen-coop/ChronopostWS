<?php

/***********
 * File: wslabelrecoveryvalue.php
 * 
 * Description: Build the object labelRecoveryValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;

class wslabelrecoveryvalue extends wsdata {
	    
	//variables remain public for SOAP use
	// public $reservationNumber;       // string
	// public $mode;       // string
	//public $numberSearch;          // string
	//public $accountNumber;      // string 
	//public $password;   // string

	
	public function setreservationNumber ($number) {
		return $this->setVariableReg($this->reservationNumber, $number, wsregex::__reg_AlphaNum);
	}
	
	public function setmode ($mode) {
		return $this->setVariableReg($this->mode, $mode, wsregex::__reg_oneSkybillMode);
	}
	
	public function setnumberSearch ($value) {
		return $this->setVariableReg($this->numberSearch, $value, wsregex::__reg_AlphaNum);
	}

	public function setaccountNumber ($value) {
		return $this->setVariableInt($this->accountNumber, $account);
	}
	
	public function setpassword ($pass) {
		$this->password = $pass;
		return true;
	}
	public function RFLcheck() {
		if( isset($this->reservationNumber, $this->mode)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " Need at least reservation number and mode to bet set");
			return false;
		}
	}
}
?>