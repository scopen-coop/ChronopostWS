<?php

/***********
 * File: ws-headervalue.php
 * 
 * Description: Build the object headerValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;

class wsheadervalue extends wsdata {
	
	//Only mandattory properties
	//optional properties to be added using setters
    //public $accountNumber;		// Numéro de compte (int)
    public $idEmit = 'CHRFR';   // Valeur fixe : CHRFR (string) - ne pas changer
    //public $identWebPro;        // string - utilisée par Chronopost
    public $subAccount;         // Numéro de sous-compte (int) - mandatory property for WSDL, even empty
	
	public function setaccountNumber ($account) {
		return $this->setVariableInt($this->accountNumber, $account);
	}
	
	public function setsubAccount ($subAccount) {
		return $this->setVariableInt($this->subAccount, $subAccount);
	}
	public function setidEmit ($value) {
		$this->idEmit = $value; //no check rule known
		return true;
	}
	public function RFLcheck() {
		if( isset($this->accountNumber, $this->subAccount, $this->idEmit)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " Headervalue does not meet minimum requirements");
			return false;
		}
	}
}
?>