<?php

/***********
 * File: wsappointmentvalue.php
 * 
 * Description: Build the object appointmentvalue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;

class wsappointmentvalue extends wsdata {
	
    
	//variables remain public for SOAP use
	//public $timeSlotEndDate;       // Date
	//public $timeSlotStartDate;       // Date
	public $timeSlotTariffLevel = "N1";          

	
	public function settimeSlotEndDate ($date) {
		return $this->setVariableReg($this->timeSlotEndDate, $date, wsregex::__reg_DateTime);
	}
	public function settimeSlotStartDate ($date) {
		return $this->setVariableReg($this->timeSlotStartDate, $date, wsregex::__reg_DateTime);
	}
	public function settimeSlotTariffLevel ($level) {
		$this->timeSlotTariffLevel = $level; //unknown rules for this property
		return true;
	}
	public function RFLcheck() {
		if( isset($this->timeSlotEndDate, $this->timeSlotStartDate)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " Need at least start and stop date time slots");
			return false;
		}
	}
}
?>