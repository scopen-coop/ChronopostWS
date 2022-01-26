<?php

/***********
 * File: ws-wsdata.php
 * 
 * Description: Build the object SkybillValue requested by WS
 *
 ************/

namespace ladromelaboratoire\chronopostws\wsdata;
use ladromelaboratoire\chronopostws\wsdata\wsregex;

abstract class wsdata {

	protected $useExceptions;
	public function __construct ($useExceptions = false) {
		$this->useExceptions = $useExceptions;
	}
	
	protected function setVariableReg (&$variable, $data, $reg) {
		$check = preg_match($reg, $data);
		if ($check === false || $check === 0) {
			if ($this->useExceptions) throw new Exception(__METHOD__ . " - $data does not match webservice requirement according to regex $reg");
			//echo ("<pre>souci avec la valeur $data et la règle $reg</pre>\r\n");
			return false;
		}
		else {
			$variable = $data;
			return true;
		}
	}
	protected function setVariableInt (&$variable, $data) {
		if (is_int($data)) {
			$variable = $data;
			return true;
		}
		else {
			if ($this->useExceptions) throw new Exception(__METHOD__ . " - $data does not match webservice requirement as int");
			// echo ("<pre>$data n'est pas un entier</pre>\r\n");
			return false;
		}
	} 
	protected function setVariableFloat (&$variable, $data) {
		if (is_float($data)) {
			$variable = $data;
			return true;
		}
		else {
			if ($this->useExceptions) throw new Exception(__METHOD__ . " - $data does not match webservice requirement as float");
			// echo ("<pre>$data n'est pas un float</pre>\r\n");
			return false;
		}
	}
	protected function setVariableBool (&$variable, $data) {
		if (is_bool($data)) {
			$variable = $data;
			return true;
		}
		else {
			if ($this->useExceptions) throw new Exception(__METHOD__ . " - $data does not match webservice requirement as boolean");
			// echo ("<pre>$data n'est pas un booleen</pre>\r\n");
			return false;
		}
	}
	public function loadArray($array) {
		if(is_array($array)) {
			foreach($array as $key => $value) {
				$func = 'set'.$key;
				if (is_callable([$this, $func])){
					$this->$func($value);
				}
				else {
					if ($this->useExceptions) throw new Exception(__METHOD__ . " - Property and/or setter method $key does not exist in object");
				}
			}
		}
		else {
			if ($this->useExceptions) throw new Exception(__METHOD__ . " - not an associative array passed as parameter");
		}
	}

	protected function _cleanObjectProperties() {
		$vars = get_object_vars($this);
		foreach($vars as $key => $var) {
			unset($this->$key);
		}
	}
	protected function RFLcheck () {
		//does nothing for use by datastructures that are not mandatory
		return true;
	}
	
	protected function RFLcheckThroughArray ($arrays) {
		foreach ($arrays as $obj) {
			//we should have an array of objects
			if (! $obj->RFLcheck()) return false;
		}
		return true;
	}
	
	public function __destruct () {
	}
}
?>