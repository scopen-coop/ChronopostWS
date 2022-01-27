<?php

/***********
 * File: shipment.php
 * 
 * Description: Build the object shipment requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsheadervalue;
use ladromelaboratoire\chronopostws\wsdata\wsshippervalue;
use ladromelaboratoire\chronopostws\wsdata\wscustomervalue;
use ladromelaboratoire\chronopostws\wsdata\wsrecipientvalue;
use ladromelaboratoire\chronopostws\wsdata\wsrefvalue;
use ladromelaboratoire\chronopostws\wsdata\wsskybillvalue;
use ladromelaboratoire\chronopostws\wsdata\wsskybillparamsvalue;
use ladromelaboratoire\chronopostws\wsdata\wsesdvalue;
use ladromelaboratoire\chronopostws\wsdata\wscustomsvalue;

class shipment extends wsdata {
	
	//mandatory properties -all others will be added dynamically
	public $password;
	public $modeRetour = 2;
	public $numberOfParcel = 1;
	
	private $twoWaysArray;
	
	public function __construct($useExceptions = false) {
		$this->useExceptions = $useExceptions;
	}
	
	/***********
	* Setters to create object properties
	*
	************/
	public function setpassword ($pass) {
		$this->password = $pass;
		return true;
	}
	public function setmodeRetour ($mode) {
		return $this->setVariableReg($this->modeRetour, $mode, wsregex::__reg_1234);
	}
	public function setnumberOfParcel ($nb) {
		return $this->setVariableInt($this->numberOfParcel, $nb);
	}
	public function setversion ($ver) {
		return $this->setVariableReg($this->version, $ver, wsregex::__reg_WsVersion);
	}
	public function setmultiParcel ($multi) {
		return $this->setVariableReg($this->multiParcel, $multi, wsregex::__reg_YesNo);
	}
	public function setheaderValue ($array) {
		$this->headerValue = new wsheadervalue($this->useExceptions);
		return $this->headerValue->loadArray($array);
	}
	public function setshipperValue ($array) {
		if (array_key_exists(0, $array)) {
			//shipping multi parcel
			foreach($array as $index => $shipper) {
				$this->shipperValue[$index] = new wsshippervalue($this->useExceptions);
				$this->shipperValue[$index]->loadArray($shipper);
			}
			return true;
		}
		else {
			$this->shipperValue[0] = new wsshippervalue($this->useExceptions);
			return $this->shipperValue[0]->loadArray($array);
		}
	}
	public function setcustomerValue ($array) {
		$this->customerValue = new wscustomervalue($this->useExceptions);
		return $this->customerValue->loadArray($array);
	}
	public function setrecipientValue ($recipients) {
		if (array_key_exists(0, $recipients)) {
			//shipping multi parcel
			foreach($recipients as $index => $recipient) {
				$this->recipientValue[$index] = new wsrecipientvalue($this->useExceptions);
				$this->recipientValue[$index]->loadArray($recipient);
			}
			return true;
		}
		else {
			$this->recipientValue[0] = new wsrecipientvalue($this->useExceptions);
			return $this->recipientValue[0]->loadArray($recipients);
		}
	}
	public function setrefValue ($refs) {
		if (array_key_exists(0, $refs)) {
			//shipping multi parcel
			foreach($refs as $index => $ref) {
				$this->refValue[$index] = new wsrefvalue($this->useExceptions);
				$this->refValue[$index]->loadArray($ref);
			}
			return true;
		}
		else {
			$this->refValue[0] = new wsrefvalue($this->useExceptions);
			return $this->refValue[0]->loadArray($refs);
		}
	}
	public function setskybillValue ($skybill) {
		if (array_key_exists(0, $skybill)) {
			//shipping multi parcel
			foreach($skybill as $index => $shipping) {
				$this->skybillValue[$index] = new wsskybillvalue($this->useExceptions);
				$this->skybillValue[$index]->loadArray($shipping);
			}
			$this->setnumberOfParcel(count($this->skybillValue));
			return true;
		}
		else {
			$this->skybillValue[0] = new wsskybillvalue($this->useExceptions);
			return $this->skybillValue[0]->loadArray($skybill);
		}
	}
	public function setskybillParamsValue ($array) {
		$this->skybillParamsValue = new wsskybillparamsvalue($this->useExceptions);
		return $this->skybillParamsValue->loadArray($array);
	}
	public function setesdValue($array) {
		$this->esdValue = new wsesdvalue($this->useExceptions);
		return $this->esdValue->loadArray($array);
	}
	public function setcustomsvalue($array) {
		$this->customsValue = new wscustomsvalue($this->useExceptions);
		return $this->customsValue->loadArray($array);
	}
	/*********
	* Helper to load an array into this object
	*
	**********/
	public function loadArray ($array) {
		parent::loadArray($array);
		$this->setnumberOfParcel(count($this->skybillValue));
	}
	/*********
	* Makes the shipment 2 ways
	*
	*********/
	public function makeTwoWays() {
		//controle du N skybill, N shipper, N recipient
		//do action
		$numskybills = count($this->skybillValue);
		$numrefs = count($this->refValue);
		$numshippers = count($this->shipperValue);
		$numrecipients = count($this->recipientValue);
		$arrayOfObjects = array();
		
		if ($numshippers  == 1 && $numrecipients == 1 && $numrefs == $numskybills) {
			foreach($this->skybillValue as $index => $skybill) {
				$this->twoWaysArray[] = array($this->shipperValue[0], $this->recipientValue[0], $this->refValue[$index], $this->skybillValue[$index]);
				$this->makeOneParcelTwoWays($this->shipperValue[0], $this->recipientValue[0], $this->refValue[$index], $this->skybillValue[$index]);
			}
		}
		else if ($numshippers  == numskybills && $numrecipients == numskybills && $numrefs == $numskybills) {
			foreach($this->skybillValue as $index => $skybill) {
				$this->twoWaysArray[] = array($this->shipperValue[$index], $this->recipientValue[$index], $this->refValue[$index], $this->skybillValue[$index]);
				$this->makeOneParcelTwoWays($this->shipperValue[$index], $this->recipientValue[$index], $this->refValue[$index], $this->skybillValue[$index]);
			}			
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " Shipper / recipient / skybill dataset do not allows 2 ways shipment");
		}
		
		unset($this->skybillValue);
		unset($this->refValue);
		unset($this->shipperValue);
		unset($this->recipientValue);
		
		foreach ($this->twoWaysArray as $parcel) {
			$this->shipperValue[] = $parcel[0];
			$this->recipientValue[] = $parcel[1];
			$this->refValue[] = $parcel[2];
			$this->skybillValue[] = $parcel[3];
			
		}
		
	}
	/********
	* Makes one parcel 2 ways
	*********/
	private function makeOneParcelTwoWays($shipper, $recipient, $refs, $skybill) {
	
		$newshipper = new wsshippervalue($this->useExceptions);
		$newrecipient = new wsrecipientvalue($this->useExceptions);
		$newrefs = new wsrefvalue($this->useExceptions);
		$newskybill = clone($skybill);
		
		if (property_exists($shipper, 'shipperAdress1')) $newrecipient->setrecipientAdress1($shipper->shipperAdress1);
		if (property_exists($shipper, 'shipperAdress2')) $newrecipient->setrecipientAdress2($shipper->shipperAdress2);
		if (property_exists($shipper, 'shipperCity')) $newrecipient->setrecipientCity($shipper->shipperCity);
		if (property_exists($shipper, 'shipperCivility')) $newrecipient->setrecipientCivility($shipper->shipperCivility);
		if (property_exists($shipper, 'shipperContactName')) $newrecipient->setrecipientContactName($shipper->shipperContactName);
		if (property_exists($shipper, 'shipperCountry')) $newrecipient->setrecipientCountry($shipper->shipperCountry);
		if (property_exists($shipper, 'shipperCountryName')) $newrecipient->setrecipientCountryName($shipper->shipperCountryName);
		if (property_exists($shipper, 'shipperEmail')) $newrecipient->setrecipientEmail($shipper->shipperEmail);
		if (property_exists($shipper, 'shipperMobilePhone')) $newrecipient->setrecipientMobilePhone($shipper->shipperMobilePhone);
		if (property_exists($shipper, 'shipperName')) $newrecipient->setrecipientName($shipper->shipperName);
		if (property_exists($shipper, 'shipperName2')) $newrecipient->setrecipientName2($shipper->shipperName2);
		if (property_exists($shipper, 'shipperPhone')) $newrecipient->setrecipientPhone($shipper->shipperPhone);
		if (property_exists($shipper, 'shipperPreAlert')) $newrecipient->setrecipientPreAlert($shipper->shipperPreAlert);
		if (property_exists($shipper, 'shipperZipCode')) $newrecipient->setrecipientZipCode($shipper->shipperZipCode);
		if (property_exists($shipper, 'shipperType')) {$newrecipient->setrecipientType($shipper->shipperType);} else {$newrecipient->setrecipientType("1");}
		
		if (property_exists($recipient, 'recipientAdress1')) $newshipper->setshipperAdress1($recipient->recipientAdress1);
		if (property_exists($recipient, 'recipientAdress2')) $newshipper->setshipperAdress2($recipient->recipientAdress2);
		if (property_exists($recipient, 'recipientCity')) $newshipper->setshipperCity($recipient->recipientCity);
		if (property_exists($recipient, 'recipientCivility')) $newshipper->setshipperCivility($recipient->recipientCivility);
		if (property_exists($recipient, 'recipientContactName')) $newshipper->setshipperContactName($recipient->recipientContactName);
		if (property_exists($recipient, 'recipientCountry')) $newshipper->setshipperCountry($recipient->recipientCountry);
		if (property_exists($recipient, 'recipientCountryName')) $newshipper->setshipperCountryName($recipient->recipientCountryName);
		if (property_exists($recipient, 'recipientEmail')) $newshipper->setshipperEmail($recipient->recipientEmail);
		if (property_exists($recipient, 'recipientMobilePhone')) $newshipper->setshipperMobilePhone($recipient->recipientMobilePhone);
		if (property_exists($recipient, 'recipientName')) $newshipper->setshipperName($recipient->recipientName);
		if (property_exists($recipient, 'recipientName2')) $newshipper->setshipperName2($recipient->recipientName2);
		if (property_exists($recipient, 'recipientPhone')) $newshipper->setshipperPhone($recipient->recipientPhone);
		if (property_exists($recipient, 'recipientPreAlert')) $newshipper->setshipperPreAlert($recipient->recipientPreAlert);
		if (property_exists($recipient, 'recipientZipCode')) $newshipper->setshipperZipCode($recipient->recipientZipCode);
		if (property_exists($recipient, 'recipientType')) $newshipper->setshipperType($recipient->recipientType);
		
		if (property_exists($refs, 'customerSkybillNumber')) $newrefs->setcustomerSkybillNumber($refs->customerSkybillNumber);
		// if (property_exists($refs, 'PCardTransactionNumber')) $newrefs->setPCardTransactionNumber($refs->PCardTransactionNumber);
		if (property_exists($refs, 'recipientRef')) $newrefs->setrecipientRef($refs->shipperRef);
		if (property_exists($refs, 'shipperRef')) $newrefs->setshipperRef($refs->recipientRef);
		if (property_exists($refs, 'idRelais')) $newrefs->setidRelais($refs->idRelais);
		
		$this->twoWaysArray[] = array($newshipper, $newrecipient, $newrefs, $newskybill);
		
	}
	/**********
	* Check object consistency
	**********/
	public function RFLcheck() {
		//RFL = Ready For Launch
		//mandatory objects
		if (!$this->RFLcheckThroughArray($this->shipperValue)) {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " ShipperValue Array not valid for WS");
			return false;
		}
		if (!$this->customerValue->RFLcheck()) {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " CustomerValue dataset not valid for WS");
			return false;
		}
		if (!$this->RFLcheckThroughArray($this->recipientValue)) {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " recipientValue Array not valid for WS");
			return false;
		}
		if (!$this->RFLcheckThroughArray($this->refValue)) {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " refValue Array not valid for WS");
			return false;
		}
		if (!$this->RFLcheckThroughArray($this->skybillValue)) {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " skybillValue Array not valid for WS");
			return false;
		}
		//optional objects - todo
		if (isset($this->esdValue) && !$this->esdValue->RFLcheck()) {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " esdValue not valid for WS");
			return false;			
		}
		if (isset($this->customsValue) && !$this->customsValue->RFLcheck()) {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " customsValue not valid for WS");
			return false;			
		}
		
		
		//consistency checks
		$numskybills = count($this->skybillValue);
		$numrefs = count($this->refValue);
		$numshippers = count($this->shipperValue);
		$numrecipients = count($this->recipientValue);
			//double check this
			$this->setnumberOfParcel(count($this->skybillValue));
			
			//One shipper, one recipient, N parcels
			if ($numshippers  == 1 && 
				$numrecipients == 1 && 
				$numrefs == $numskybills) {
				return true;
			}
			//One parcel per shipper and recipient
			else if ($numskybills == $numshippers &&
					$numskybills == $numrecipients &&
					$numskybills == $numrefs){
				return true;
			}
			//One shipper, N recipients, N parcels
			else if ($numshippers == 1 &&
					$numskybills == $numrecipients &&
					$numskybills == $numrefs){
				return true;
			}
			else {
				return false;
			}
		//nothing weird found
		return true;
	}

}
?>