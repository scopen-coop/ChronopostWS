<?php

/***********
 * File: wscustomsvalue.php
 * 
 * Description: Build the object customsValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsarticlesvalue;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;

class wscustomsvalue extends wsdata {
	
    
	//All variables created dynamically
	//articlesValue
	//bagNumber
	//clearanceCleared
	//currency
	//description
	//descriptionInLanguage
	//eori
	//incoterm
	//language
	//numberOfItems
	//value
	//vatNumber
	//descriptionInEnglish
	
	

	
	public function setarticlesValue ($array) {
		if (array_key_exists(0, $array)) {
			foreach($array as $index => $article) {
				$this->articlesValue[$index] = new wsarticlesvalue($this->useExceptions);
				$this->articlesValue[$index]->loadArray($article);
			}
			return true;
		}
		else {
			$this->articlesValue[0] = new wsarticlesvalue($this->useExceptions);
			return $this->articlesValue[0]->loadArray($array);
		}	
	}
	
	public function setbagNumber ($value) {
		return $this->setVariableReg($this->bagNumber, $value, wsregex::__reg_string30);
	}
	public function setclearanceCleared ($value) {
		return $this->setVariableReg($this->clearanceCleared, $value, wsregex::__reg_clearanceCleared);
	}
	public function setcurrency ($value) {
		return $this->setVariableReg($this->currency, $value, wsregex::__reg_CurrencyCode);
	}
	public function setdescription ($value) {
		return $this->setVariableReg($this->description, $value, wsregex::__reg_string100);
	}
	public function setdescriptionInLanguage ($value) {
		return $this->setVariableReg($this->descriptionInLanguage, $value, wsregex::__reg_string100);
	}
	public function seteori ($value) {
		return $this->setVariableReg($this->eori, $value, wsregex::__reg_Eori);
	}
	public function setincoterm ($value) {
		return $this->setVariableReg($this->incoterm, $value, wsregex::__reg_Incoterm);
	}
	public function setlanguage ($value) {
		return $this->setVariableReg($this->language, $value, wsregex::__reg_locale);
	}
	public function setnumberOfItems ($value) {
		return $this->setVariableInt($this->numberOfItems, $value);
	}
	public function setvalue ($value) {
		return $this->setVariableFloat($this->value, $value);
	}
	public function setvatNumber ($value) {
		return $this->setVariableReg($this->vatNumber, $value, wsregex::__reg_VAT_EU);
	}
	public function setdescriptionInEnglish ($value) {
		return $this->setVariableReg($this->descriptionInEnglish, $value, wsregex::__reg_string100);
	}
	public function RFLcheck() {
		if( $this->RFLcheckThroughArray($this->articlesValue) && isset($this->clearanceCleared, $this->currency, $this->description, $this->descriptionInLanguage, $this->incoterm, $this->value, $this->descriptionInEnglish)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " Customs object does not meet minimum requirements, please read the doc");
			return false;
		}
	}
}
?>