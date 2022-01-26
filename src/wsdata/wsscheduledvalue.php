<?php

/***********
 * File: wsscheduledvalue.php
 * 
 * Description: Build the object scheduledValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;
use ladromelaboratoire\chronopostws\wsdata\wsdata;

class wsscheduledvalue extends wsdata {
	
    
	//variables remain public for SOAP use
	//public $appointmentValue;       // object
	//public $expirationDate;       // Date
	//public $sellByDate;          // Date

	
	public function setappointmentValue ($array) {
		if (array_key_exists(0, $array)) {
			//shipping multi parcel
			foreach($array as $index => $appointment) {
				$this->appointmentValue[$index] = new wsappointmentvalue($this->useExceptions);
				$this->appointmentValue[$index]->loadArray($appointment);
			}
			return true;
		}
		else {
			$this->appointmentValue[0] = new wsappointmentvalue($this->useExceptions);
			return $this->appointmentValue[0]->loadArray($array);
		}	
	}
	
	public function setexpirationDate ($date) {
		return $this->setVariableReg($this->expirationDate, $date, wsregex::__reg_Date);
	}
	public function setsellByDate ($date) {
		return $this->setVariableReg($this->sellByDate, $date, wsregex::__reg_Date);
	}
	public function RFLcheck() {
		if( $this->RFLcheckThroughArrayisset($this->appointmentValue) && isset($this->expirationDate, $this->sellByDate)) {
			return true;
		}
		else {
			if ($this->useExceptions) throw new wsdataexception(__METHOD__ . " ScheduledValue not properly defined");
			return false;
		}
	}
}
?>