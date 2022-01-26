<?php

/***********
 * File: ws-customervalue.php
 * 
 * Description: Build the object customerValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;

class wscustomervalue extends wsdata {

	//only mandatory properties as default
	//optional properties added using setters
	//public $customerAdress1;       // string
	//public $customerAdress2;       // string
	//public $customerCity;          // string
	//public $customerCivility;      // string E|L|M
	//public $customerContactName;   // string - mandatory if ESD
	//public $customerCountry;       // string
	//public $customerCountryName;   // string
	//public $customerEmail;         // string
	//public $customerMobilePhone;   // string
	//public $customerName;          // string
	//public $customerName2;         // string
	//public $customerPhone;         // string
	public $customerPreAlert = 0;	// string
	public $customerZipCode;       // string	
	public $printAsSender = 'N';   // string Y|N
	
	public function setcustomerAdress1 ($address) {
		return $this->setVariableReg($this->customerAdress1, $address, wsregex::__reg_Address);
	}
	
	public function setcustomerAdress2 ($address) {
		return $this->setVariableReg($this->customerAdress2, $address, wsregex::__reg_Address);
	}

	public function setcustomerCity ($city) {
		return $this->setVariableReg($this->customerCity, $city, wsregex::__reg_City);
	}
	
	public function setcustomerCivility ($civ) {
		return $this->setVariableReg($this->customerCivility, $civ, wsregex::__reg_Civility);
	}
	
	public function setcustomerContactName ($contact) {
		return $this->setVariableReg($this->customerContactName, $contact, wsregex::__reg_ContactName);
	}
	
	public function setcustomerCountry ($countrycode) {
		return $this->setVariableReg($this->customerCountry, $countrycode, wsregex::__reg_CountryCode);
	}	
	
	public function setcustomerCountryName ($countryname) {
		return $this->setVariableReg($this->customerCountryName, $countryname, wsregex::__reg_CountryName);
	}
	
	public function setcustomerEmail ($email) {
		return $this->setVariableReg($this->customerEmail, $email, wsregex::__reg_Email);
	}
	
	public function setcustomerMobilePhone ($phone) {
		return $this->setVariableReg($this->customerMobilePhone, $phone, wsregex::__reg_PhoneNumber);
	}

	public function setcustomerPhone ($phone) {
		return $this->setVariableReg($this->customerPhone, $phone, wsregex::__reg_PhoneNumber);
	}

	public function setcustomerName ($contact) {
		return $this->setVariableReg($this->customerName, $contact, wsregex::__reg_ContactName);
	}

	public function setcustomerName2 ($contact) {
		return $this->setVariableReg($this->customerName2, $contact, wsregex::__reg_ContactName);
	}
	
	public function setcustomerZipCode ($zipcode) {
		return $this->setVariableReg($this->customerZipCode, $zipcode, wsregex::__reg_ZipCode);
	}

	public function setcustomerPreAlert ($alert) {
		return $this->setVariableReg($this->customerPreAlert, $alert, wsregex::__reg_PreAlert);
	}

	public function setcustomerPrintAsSender ($status) {
		return $this->setVariableReg($this->printAsSender, $status, wsregex::__reg_YesNo);
	}
	public function RFLcheck() {
		if( isset($this->customerAdress1, $this->customerCity, $this->customerCivility, $this->customerCountry, $this->customerEmail, $this->customerName, $this->customerPhone, $this->customerZipCode)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " Missing data in minimum customer definition. please have a look at doc");
			return false;
		}
	}
}
?>