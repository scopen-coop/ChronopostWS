<?php

/***********
 * File: ws-recipientvalue.php
 * 
 * Description: Build the object recipientValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;

class wsrecipientvalue extends wsdata {
    
	//Only mandatory properties as default
	//optional properties added using setters
	public $recipientAdress1;       // string
	//public $recipientAdress2;       // string
	public $recipientCity;          // string
	//public $recipientCivility;      // string E|L|M
	//public $recipientContactName;   // string
	public $recipientCountry;       // string
	//public $recipientCountryName;   // string
	public $recipientEmail;         // string
	//public $recipientMobilePhone;   // string
	public $recipientName;          // string
	//public $recipientName2;         // string
	public $recipientPhone;         // string
	public $recipientPreAlert = 0;	  // string 0|22
	public $recipientZipCode;       // string	
	//public $recipientType = 1;       // string	1|2
	

	public function setrecipientAdress1 ($address) {
		return $this->setVariableReg($this->recipientAdress1, $address, wsregex::__reg_Address);
	}
	
	public function setrecipientAdress2 ($address) {
		return $this->setVariableReg($this->recipientAdress2, $address, wsregex::__reg_Address);
	}
	
	public function setrecipientCity ($city) {
		return $this->setVariableReg($this->recipientCity, $city, wsregex::__reg_City);
	}
	
	public function setrecipientCivility ($civ) {
		return $this->setVariableReg($this->recipientCivility, $civ, wsregex::__reg_Civility);
	}
	
	public function setrecipientContactName ($contact) {
		return $this->setVariableReg($this->recipientContactName, $contact, wsregex::__reg_ContactName);
	}
	
	public function setrecipientCountry ($countrycode) {
		return $this->setVariableReg($this->recipientCountry, $countrycode, wsregex::__reg_CountryCode);
	}	
	
	public function setrecipientCountryName ($countryname) {
		return $this->setVariableReg($this->recipientCountryName, $countryname, wsregex::__reg_CountryName);
	}
	
	public function setrecipientEmail ($email) {
		return $this->setVariableReg($this->recipientEmail, $email, wsregex::__reg_Email);
	}
	
	public function setrecipientMobilePhone ($phone) {
		return $this->setVariableReg($this->recipientMobilePhone, $phone, wsregex::__reg_PhoneNumber);
	}

	public function setrecipientPhone ($phone) {
		return $this->setVariableReg($this->recipientPhone, $phone, wsregex::__reg_PhoneNumber);
	}

	public function setrecipientName ($contact) {
		return $this->setVariableReg($this->recipientName, $contact, wsregex::__reg_ContactName);
	}

	public function setrecipientName2 ($contact) {
		return $this->setVariableReg($this->recipientName2, $contact, wsregex::__reg_ContactName);
	}
	
	public function setrecipientZipCode ($zipcode) {
		return $this->setVariableReg($this->recipientZipCode, $zipcode, wsregex::__reg_ZipCode);
	}

	public function setrecipientPreAlert ($alert) {
		return $this->setVariableReg($this->recipientPreAlert, $alert, wsregex::__reg_PreAlert);
	}

	public function setrecipientType ($type) {
		return $this->setVariableReg($this->recipientType, $type, wsregex::__reg_ContactType);
	}
	public function RFLcheck() {
		if( isset($this->recipientAdress1, $this->recipientCity, $this->recipientCountry, $this->recipientEmail, $this->recipientName, $this->recipientPhone, $this->recipientZipCode)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " recipient dataset does not meet minimum requirements");
			return false;
		}
	}
}
?>