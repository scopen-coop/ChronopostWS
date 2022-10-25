<?php

/***********
 * File: ws-shippervalue.php
 * 
 * Description: Build the object shipperValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;

class wsshippervalue extends wsdata {
    
	//variables remain public for SOAP use
	// public $shipperAdress1;       // string
	//public $shipperAdress2;       // string
	// public $shipperCity;          // string
	// public $shipperCivility;      // string E|L|M
	//public $shipperContactName;   // string
	// public $shipperCountry;       // string
	//public $shipperCountryName;   // string
	// public $shipperEmail;         // string
	//public $shipperMobilePhone;   // string
	// public $shipperName;          // string
	//public $shipperName2;         // string
	// public $shipperPhone;         // string
	public $shipperPreAlert = 0;	  // string
	// public $shipperZipCode;       // string	
	//public $shipperType = 1;       // string	
	
	public function setshipperAdress1 ($address) {
		return $this->setVariableReg($this->shipperAdress1, $address, wsregex::__reg_Address);
	}
	
	public function setshipperAdress2 ($address) {
		return $this->setVariableReg($this->shipperAdress2, $address, wsregex::__reg_Address);
	}
	
	public function setshipperCity ($city) {
		return $this->setVariableReg($this->shipperCity, $city, wsregex::__reg_City);
	}
	
	public function setshipperCivility ($civ) {
		return $this->setVariableReg($this->shipperCivility, $civ, wsregex::__reg_Civility);
	}
	
	public function setshipperContactName ($contact) {
		return $this->setVariableReg($this->shipperContactName, $contact, wsregex::__reg_ContactName);
	}
	
	public function setshipperCountry ($countrycode) {
		return $this->setVariableReg($this->shipperCountry, $countrycode, wsregex::__reg_CountryCode);
	}	
	
	public function setshipperCountryName ($countryname) {
		return $this->setVariableReg($this->shipperCountryName, $countryname, wsregex::__reg_CountryName);
	}
	
	public function setshipperEmail ($email) {
		return $this->setVariableReg($this->shipperEmail, $email, wsregex::__reg_Email);
	}
	
	public function setshipperMobilePhone ($phone) {
		return $this->setVariableReg($this->shipperMobilePhone, $phone, wsregex::__reg_PhoneNumber);
	}

	public function setshipperPhone ($phone) {
		return $this->setVariableReg($this->shipperPhone, $phone, wsregex::__reg_PhoneNumber);
	}

	public function setshipperName ($contact) {
		return $this->setVariableReg($this->shipperName, $contact, wsregex::__reg_ContactName);
	}

	public function setshipperName2 ($contact) {
		return $this->setVariableReg($this->shipperName2, $contact, wsregex::__reg_ContactName);
	}
	
	public function setshipperZipCode ($zipcode) {
		return $this->setVariableReg($this->shipperZipCode, $zipcode, wsregex::__reg_ZipCode);
	}

	public function setshipperPreAlert ($alert) {
		return $this->setVariableReg($this->shipperPreAlert, $alert, wsregex::__reg_PreAlert);
	}

	public function setshipperType ($type) {
		return $this->setVariableReg($this->shipperType, $type, wsregex::__reg_ContactType);
	}
	public function RFLcheck() {
		if( isset($this->shipperAdress1, $this->shipperCity, $this->shipperCountry, $this->shipperEmail, $this->shipperName, $this->shipperPhone, $this->shipperZipCode)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " One ShipperValue object does not meet minimum requirements");
			return false;
		}
	}
}
?>