<?php

/***********
 * File: wsarticlesvalue.php
 * 
 * Description: Build the object articlesValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;

class wsarticlesvalue extends wsdata {
	
    
	//All variables created dynamically
	//content
	//contentInLanguage
	//grossWeight
	//hscode
	//netWeight
	//origin
	//position
	//quantity
	//regime
	//value
	

	public function setcontent ($value) {
		return $this->setVariableReg($this->content, $value, wsregex::__reg_string200);
	}
	public function setcontentInLanguage ($value) {
		return $this->setVariableReg($this->contentInLanguage, $value, wsregex::__reg_string200);
	}
	public function setgrossWeight ($value) {
		return $this->setVariableFloat($this->grossWeight, $value);
	}
	public function setnetWeight ($value) {
		return $this->setVariableFloat($this->netWeight, $value);
	}
	public function setposition ($value) {
		return $this->setVariableInt($this->position, $value);
	}
	public function setquantity ($value) {
		return $this->setVariableInt($this->quantity, $value);
	}
	public function sethscode ($value) {
		$this->hscode = $value; //unknown check rules
		return true;
	}
	public function setorigin ($value) {
		return $this->setVariableReg($this->origin, $value, wsregex::__reg_string100);
	}
	public function setregime ($value) {
		return $this->setVariableReg($this->regime, $value, wsregex::__reg_CustomsRegime);
	}
	public function setvalue ($value) {
		return $this->setVariableFloat($this->value, $value);
	}
	public function RFLcheck() {
		if( isset($this->content, $this->contentInLanguage, $this->hscode, $this->netWeight, $this->position, $this->quantity, $this->value)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " ArticleValue for $this->content not defined properly. Please look at the doc");
			return false;
		}
	}
}
?>