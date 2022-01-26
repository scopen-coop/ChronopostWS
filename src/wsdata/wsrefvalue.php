<?php

/***********
 * File: ws-refvalue.php
 * 
 * Description: Build the object refValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;

class wsrefvalue extends wsdata {
	
	private CONST __CSNsize = 15;
    
	//variables remain public for SOAP use
	//public $customerSkybillNumber;       // string
	public $PCardTransactionNumber;       // string - useless in appearance but mandatory
	//public $recipientRef;          // string
	//public $shipperRef;      // string 
	//public $idRelais;   // string

	
	public function setcustomerSkybillNumber ($number) {
		$number = substr($number, 0, self::__CSNsize);
		return $this->setVariableReg($this->customerSkybillNumber, $number, wsregex::__reg_CustomerSkybillNumber);
	}
	
	public function setrecipientRef ($ref) {
		return $this->setVariableReg($this->recipientRef, $ref, wsregex::__reg_Refs);
	}

	public function setshipperRef ($ref) {
		return $this->setVariableReg($this->shipperRef, $ref, wsregex::__reg_Refs);
	}
	
	public function setidRelai ($id) {
		return $this->setVariableReg($this->idRelais, $id, wsregex::__reg_IdRelai);
	}
	public function RFLcheck() {
		if( isset($this->shipperRef)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " refValue does not meet minimum requirements");
			return false;
		}
	}
}
?>