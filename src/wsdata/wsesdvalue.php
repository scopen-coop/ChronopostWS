<?php

/***********
 * File: ws-esdvalue.php
 * 
 * Description: Build the object esdValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;

class wsesdvalue extends wsdata {
    private CONST __3Months = 7776000; //90d * 86400s;
	
	//variables remain public for SOAP use
	// $closingDateTime;
	// $height;
	// $length;
	// $width;
	// $retrievalDateTime;
	// $shipperBuildingFloor;
	// $shipperCarriesCode;
	// $shipperServiceDirection;
	// $ltAImprimerParChronopost;
	// $nombreDePassageMaximum;
	// $refEsdClient;
	// $numberOfParcel;
	// $parcelsNumber;
	// $codeDepotColReq;
	// $numColReq;

	public function setclosingDateTime ($value) {
		return $this->setVariableReg($this->closingDateTime, $value, wsregex::__reg_DateTime);
	}
	public function setheight ($value) {
		return $this->setVariableFloat($this->height, $value);
	}
	public function setlength ($value) {
		return $this->setVariableFloat($this->length, $value);
	}
	public function setwidth ($value) {
		return $this->setVariableFloat($this->width, $value);
	}
	public function setretrievalDateTime ($value) {
		$now = time();
		if ($this->setVariableReg($this->retrievalDateTime, $value, wsregex::__reg_DateTime)) {
			$delta = strtotime($value) - $now;
			if ($delta > 0 && $delta < self::__3Months) {
				return true;
			}
			else {
				unset($this->retrievalDateTime);
				if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " retrievalDateTime not within Now and Now+3months range");
				return false
			}
		}
		else {
			return false
		}
	}
	public function setshipperBuildingFloor ($value) {
			return $this->setVariableReg($this->shipperBuildingFloor, $value, wsregex::__reg_string32);
	}
	public function setshipperCarriesCode ($value) {
			return $this->setVariableReg($this->shipperCarriesCode, $value, wsregex::__reg_string32);
	}
	public function setshipperServiceDirection ($value) {
			return $this->setVariableReg($this->shipperServiceDirection, $value, wsregex::__reg_string32);
	}
	public function setspecificInstructions ($value) {
			return $this->setVariableReg($this->specificInstructions, $value, wsregex::__reg_string255);
	}
	public function setltAImprimerParChronopost ($value) {
			return $this->setVariableBool($this->ltAImprimerParChronopost, $value);
	}
	public function setnombreDePassageMaximum ($value) {
			return $this->setVariableInt($this->nombreDePassageMaximum, $value);
	}
	public function setrefEsdClient ($value) {
			return $this->setVariableReg($this->refEsdClient, $value, wsregex::__reg_string32);
	}
	public function setnumberOfParcel ($value) {
			return $this->setVariableInt($this->numberOfParcel, $value);
	}
	public function setparcelesNumber ($value) {
			return $this->setVariableReg($this->parcelesNumber, $value, wsregex::__reg_string255);
	}	
	public function RFLcheck() {
		if( isset($this->closingDateTime, $this->height, $this->length, $this->width, $this->retrievalDateTime, $this->shipperBuildingFloor, $this->shipperCarriesCode, $this->shipperServiceDirection, $this->refEsdClient)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " esdValue does not meet minimum requirements. Please read the doc");
			return false;
		}
	}
}
?>