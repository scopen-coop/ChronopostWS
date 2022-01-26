<?php

/***********
 * File: ws-skybillvalue.php
 * 
 * Description: Build the object SkybillValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;

class wsskybillvalue extends wsdata {
    
	//by default, declaring ony properties that are mandatory.
	//optional properties can be added by calling the setters methods
	//public $bulkNumber; //Nombre total de colis
	//Contre remboursement - code service 09
	//public $codCurrency = 'EUR'; //string Devise de contre remboursement
	//public $codValue; //int valeur de contre remboursement
	//public $toTheOrderOf; //Order du chèque d contre remboursement
	//public $content1;
	//public $content2;
	//public $content3;
	//public $content4;
	//public $content5;
	//Customs mandatory if shipping INTL
	//public $customsCurrency = 'EUR';
	//public $customsValue;
	public $evtCode = 'DC';
	//Insurance - causes additional costs
	//public $insuredCurrency = 'EUR';
	//public $insuredValue;
	//public $latitude;
	//public $longitude;
	//public $masterSkybillNumber; //numéro du premier colis d'une expé
	public $objectType = 'MAR'; //shipment type DOC|MAR document or goods
	//public $portCurrency = 'EUR';
	//public $portValue;
	public $productCode = '01';
	//public $qualite;
	public $service = '136';
	public $shipDate; //YYYY-MM-DDTHH:MM:SS
	public $shipHour; //NN
	//public $skybillRank; //rang du colis sur le nombre total
	//public $source;
	public $weight;
	public $weightUnit = 'KGM'; //Default kilogramme
	public $height = 0.0; //centimetres
	public $length = 0.0; //centimetres
	public $width = 0.0; //centimetres
	//public $as;
	//public $subAccount;
	//public $carrier; //0 LINEX | 1 SF EXPRESS
	//public $skybillNumber;
	//public $skybillBackNumber
	//public $alternateProductCode;
	//public $labelNumber;
	
	
	public function setbulkNumber ($num) {
		return $this->setVariableReg($this->bulkNumber, $num, wsregex::__reg_AnyString);
	}
	public function setcodCurrency($code) {
		return $this->setVariableReg($this->codCurrency, $code, wsregex::__reg_CurrencyCode);
	}
	public function setcodValue ($value) {
		return $this->setVariableInt($this->codValue, $value);
	}
	public function settoTheOrderOf ($value) {
		$this->toTheOrderOf($value);
		return true;
	}
	public function setcustomsCurrency($code) {
		return $this->setVariableReg($this->customsCurrency, $code, wsregex::__reg_CurrencyCode);
	}

	public function setinsuredCurrency($code) {
		return $this->setVariableReg($this->insuredCurrency, $code, wsregex::__reg_CurrencyCode);
	}

	public function setportCurrency($code) {
		return $this->setVariableReg($this->portCurrency, $code, wsregex::__reg_CurrencyCode);
	}
	
	public function setcontent1($content) {
		return $this->setVariableReg($this->content1, $content, wsregex::__reg_Content);
	}
	
	public function setcontent2($content) {
		return $this->setVariableReg($this->content2, $content, wsregex::__reg_Content);
	}

	public function setcontent3($content) {
		return $this->setVariableReg($this->content3, $content, wsregex::__reg_Content);
	}

	public function setcontent4($content) {
		return $this->setVariableReg($this->content4, $content, wsregex::__reg_Content);
	}

	public function setcontent5($content) {
		return $this->setVariableReg($this->content5, $content, wsregex::__reg_Content);
	}
	
	public function setcustomsValue ($value) {
		return $this->setVariableInt($this->customsValue, $value);
	}
	
	public function setinsuredValue ($value) {
		return $this->setVariableInt($this->insuredValue, $value);
	}
	public function setmasterSkybillNumber ($content) {
		return $this->setVariableReg($this->masterSkybillNumber, $content, wsregex::__reg_AnyString);
	}
	public function setobjectType ($type) {
		return $this->setVariableReg($this->objectType, $type, wsregex::__reg_ObjectType);
	}
	public function setportValue($value) {
		return $this->setVariableInt($this->portValue, $value);
	}
	public function setproductCode($code) {
		return $this->setVariableReg($this->productCode, $code, wsregex::__reg_ProductCodes);
	}
	public function setalternateProductCode($code) {
		return $this->setVariableReg($this->alternateProductCode, $code, wsregex::__reg_ProductCodes);
	}
	public function setservice($code) {
		return $this->setVariableReg($this->service, $code, wsregex::__reg_Services);
	}
	public function setshipDate($date = null) {
		// if (is_null($date)) $date = date('Y-m-d\TH:i:s');
		if (is_null($date)) $date = date('Y-m-d');
		return $this->setVariableReg($this->shipDate, $date, wsregex::__reg_Date);
	}
	public function setshipHour($hour = null) {
		if (is_null($hour)) $hour = date('H');
		return $this->setVariableReg($this->shipHour, $hour, wsregex::__reg_Hour);
	}
	public function setskybillRank ($value = null) {
		$this->skybillRank = $value;
		return true;
	}
	public function setweight ($value) {
		return $this->setVariableFloat($this->weight, $value);
	}
	public function setweightUnit ($value) {
		//only kilogram supported by WS - keep KGM default value
		return true;
	}
	public function setheight ($value) {
		return $this->setVariableInt($this->height, $value);
	}
	public function setlength ($value) {
		return $this->setVariableInt($this->length, $value);
	}
	public function setwidth ($value) {
		return $this->setVariableInt($this->width, $value);
	}
	public function setas ($value) {
		return $this->setVariableReg($this->as, $value, wsregex::__reg_AsProducts);
	}
	public function setsubAccount($value) {
		return $this->setVariableReg($this->subAccount, $value, wsregex::__reg_SubAccountNumber);
	}
	public function setcarrier ($value) {
		return $this->setVariableReg($this->carrier, $value, wsregex::__reg_01);
	}
	public function RFLcheck() {
		if( isset($this->evtCode, $this->objectType, $this->productCode, $this->service, $this->shipDate, $this->shipHour, $this->height, $this->length, $this->width, $this->weightUnit)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " Skybillvalue does not meet minimum requirements");
			return false;
		}
	}
}
?>