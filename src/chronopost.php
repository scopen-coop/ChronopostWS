<?php
namespace ladromelaboratoire\chronopostws;
use ladromelaboratoire\chronopostws\shipment;
use ladromelaboratoire\chronopostws\tracking;
use ladromelaboratoire\chronopostws\wsdata\wslabelrecoveryvalue;
use ladromelaboratoire\chronopostws\exceptions\wsexception;
use ladromelaboratoire\chronopostws\exceptions\wsdataexception;
use ladromelaboratoire\chronopostws\utils\fileHandler;
use SoapClient;


class chronopost {

	private CONST __default_path_log = './logs/';
	private CONST __soap_options = array(
		"soap_version" => SOAP_1_2,
		"encoding" => "UTF-8",
		"trace" => true,
		"exceptions" => false,
		"connection_timeout" => 10
	);
	private CONST __shipping_wsdl = 'https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl';
	private CONST __tracking_wsdl = 'https://ws.chronopost.fr/tracking-cxf/TrackingServiceWS?wsdl';
	private CONST __quickcost_wsdl = 'https://ws.chronopost.fr/quickcost-cxf/QuickcostServiceWS?wsdl';
	
	private $shippingSC;
	private $trackingSC;
	private $quickcostSC;
	private $debugMode;
	private $useExceptions;

	public function __construct($useExceptions = false, $debug = false) {
		$this->debugMode = $debug;
		$this->useExceptions = $useExceptions;
		$this->shipment = new shipment($this->useExceptions);
		$this->tracking = new tracking($this->useExceptions);
		// $this->quickcost = new quickcost($this->useExceptions);
	}
	
	/*********
	* Get labels array to forge shipment object
	* Can transform shipment in two waus shipment
	* Makes call to Chronopost
	* Get response and append Shipment object
	* Can trigger label recovery
	***********/
	public function makeShippingLabels ($labelsdata, $automaticLabelRecovery = true, $twoWays = false) {
		
		//Soap dataset forging
		
		$this->shipment->loadArray($labelsdata);
		$this->logObject("_shipping_obj_init", $this->shipment, self::__default_path_log);
		
		//Makes it 2 ways
		if ($twoWays) {
			$this->shipment->makeTwoWays();
			$this->logObject("_shipping_obj_2ways", $this->shipment, self::__default_path_log);
		}
		
		//Force reservation to get a common way for labels recovery
		$this->shipment->skybillParamsValue->forceReservation();
		
		//Makes call if Ready For Launch
		if ($this->shipment->RFLcheck()) {
			
			if (!is_object($this->shippingSC)) $this->shippingSC = $this->createSC(self::__shipping_wsdl);
			$response = $this->shippingSC->shippingMultiParcelV4($this->shipment);
			$this->logLastRq("shipping", $this->shippingSC);
			
		}
		else {
			if ($this->useExceptions) throw new wsdataexception (__METHOD__ . " Shipping object fails RFLcheck");
			return false;
		}
		
		// Append $this->shipment with response values
		if($response->return->errorCode == 0 && isset($response->return->reservationNumber)) {
			$this->shipment->reservationNumber = $response->return->reservationNumber;
			
			//Add skybillNumber to each skybill
			//SOAP service does not return an array when only one item is sent back
			if(is_countable($response->return->resultMultiParcelValue)) {
				foreach($response->return->resultMultiParcelValue as $key => $value) {
					$this->shipment->skybillValue[$key]->skybillNumber = $value->skybillNumber;
				}
			}
			else {
				$this->shipment->skybillValue[0]->skybillNumber = $response->return->resultMultiParcelValue->skybillNumber;
			}
			$this->logObject("_shipping_obj_withResponse", $this->shipment, self::__default_path_log);
			//Labels recorevy
			if ($automaticLabelRecovery) $this->getReservedLabels();
		}
		else {
			if ($this->useExceptions) throw new wsexception (__METHOD__ . " shippingMultiparcelV4 failed, error code $response->return->errorCode | $response->return->errorMessage");
			return false;
		}
		
		return true;
	}
	
	/************
	* Recovers labels for the shipment object
	* Append data to shipment objects
	*************/
	
	public function getReservedLabels() {
		$labels = new wslabelrecoveryvalue();
		if (!is_object($this->shippingSC)) $this->shippingSC = $this->createSC(self::__shipping_wsdl);

		$labels->setreservationNumber($this->shipment->reservationNumber);
		foreach($this->shipment->skybillParamsValue->getModesArray() as $mode) {

			$labels->setmode($mode);
			$this->logObject("_labels_obj_" . $mode, $labels, self::__default_path_log);
			if ($labels->RFLcheck()) {
				$response = $this->shippingSC->getReservedSkybillWithTypeAndMode($labels);
				$this->logLastRq("getlabels_" . $mode, $this->shippingSC);
			}
			else {
				if ($this->useExceptions) throw new wsdataexception (__METHOD__ . " LabelrecoveryValue object fails RFLcheck");
				return false;
			}
		
		
			if($response->return->errorCode == 0) {
				//stores labels in $this->shipment
				$this->shipment->labels[$mode] = $response->return->skybill;
			}
			else {
				if ($this->useExceptions) throw new wsexception (__METHOD__ . " skybills not retreived, error code $response->return->errorCode | $response->return->errorMessage");
				return false;
			}
		}
		return true;
	}
	
	/************
	* cancel Skybill
	* 
	*************/

	public function cancelSkybill($labeldata) {
		
		if (is_array($labeldata) && array_key_exists('skybillNumber', $labeldata) && array_key_exists('accountNumber', $labeldata)) {
			$this->tracking->setcancelSkybillValue($labeldata);
			if($this->tracking->cancelSkybillValue->RFLcheck()) {
				$this->logObject("_cancel_obj", $this->tracking->cancelSkybillValue, self::__default_path_log);
				
				if (!is_object($this->trackingSC)) $this->trackingSC = $this->createSC(self::__tracking_wsdl);
				$response = $this->trackingSC->cancelSkybill($this->tracking->cancelSkybillValue);
				$this->logLastRq("cancelSkybill", $this->trackingSC);

				if($response->return->errorCode == 0) {
					//Sucess
					return true;
				}
				else {
					if ($this->useExceptions) throw new wsexception (__METHOD__ . " skybill $this->tracking->cancelSkybillValue->skybillNumber not cancelled, error code $response->return->errorCode | $response->return->errorMessage");
					return false;
				}
			}
			else {
				if ($this->useExceptions) throw new wsdataexception (__METHOD__ . " cancelSkybillValue fails RFLcheck");
				return false;
			}
		}
		else {
			if ($this->useExceptions) throw new wsdataexception (__METHOD__ . " cancelSkybillValue seems invalid before treatments");
			return false;
		}
	}
	
	
	/**********
	* get full tracking for a skybill number
	***********/
	/*
	public function getTrackingParcel($labelNumber) {
		$trackingData = new wstrackingoneparcelvalue();
		$trackingData->setskybillNumber($labelNumber);
		
		if (!is_object($this->trackingSC)) $this->trackingSC = $this->createSC(self::__tracking_wsdl);
		$response = $this->trackingSC->trackSkybillV2($searchPodData);

		if ($response->return->errorCode == 0) {
			$tracking = (array) $response->return->listEventInfoComp;
		}
		else {
			if ($this->useExceptions) throw new wsexception (__METHOD__ . " Delivery tracking issue, error code $response->return->errorCode | $response->return->errorMessage");
		}
		unset($trackingData);
		return $tracking;
		
		
	}
	*/
	/************
	* Delivery evidence recovery
	*************/
	/*
	public function getDeliveryEvidence ($searchdata) {
		$searchPodData = new wssearchpodvalue();
		$searchPodData->loadArray($searchdata);
		
		if (!is_object($this->trackingSC)) $this->trackingSC = $this->createSC(self::__tracking_wsdl);
		$response = $this->trackingSC->searchPOD($searchPodData);
		
		if ($response->return->errorCode == 0) {
			if ($response->return->podPresente) {
				$pod['pod'] = $response->return->pod;
				$pod['podFormat'] = $response->return->formatPOD;
				$pod['statusCode'] = $response->return->statusCode;
				$pod['skybillNumber'] = $response->return->skybillNumber;
				$pod['filename'] = $pod['skybillNumber'] . "_pod" . "." . $pod['podFormat'];
			}
			else {
				if ($this->useExceptions) throw new wsexception (__METHOD__ . " DeliveryEvidence not found, error code $response->return->errorCode | $response->return->errorMessage");
			}
		}
		else {
			if ($this->useExceptions) throw new wsexception (__METHOD__ . " DeliveryEvidence issue, error code $response->return->errorCode | $response->return->errorMessage");
		}
		unset($searchPodData);
		return $pod;
	}
	*/
	/************
	* search Cost for one parcel
	*************/
	/*
	public function getDeliveryCost($data) {
		if (is_array($data) {
			$this->quickcost->setquickcostValue($data);
			if($this->quickcost->quickcostValue->RFLcheck()) {
				$this->logObject("_quickcost_obj", $this->quickcost->quickcostValue, self::__default_path_log);
				
				// if (!is_object($this->trackingSC)) $this->trackingSC = $this->createSC(self::__tracking_wsdl);
				// $response = $this->trackingSC->cancelSkybill($this->tracking->cancelSkybillValue);
				// $this->logLastRq("cancelSkybill", $this->trackingSC);
				
				// if($response->return->errorCode == 0) {
					Sucess
					// return true;
				// }
				// else {
					// if ($this->useExceptions) throw new wsexception (__METHOD__ . " skybill $this->tracking->cancelSkybillValue->skybillNumber not cancelled, error code $response->return->errorCode | $response->return->errorMessage");
					// return false;
				// }
				
			}
			else {
				if ($this->useExceptions) throw new wsdataexception (__METHOD__ . " quickcostValue fails RFLcheck");
				return false;
			}
			
			
		}
		else {
			if ($this->useExceptions) throw new wsdataexception (__METHOD__ . " quickcostValue seems invalid before treatments");
			return false;
		}
	}		
	}
	
	*/
	/********
	* Logs last SOAP call
	*********/
	private function logLastRq ($filename, $SCobject, $path = false) {
		if ($this->debugMode) {
			if(!$path) $path = self::__default_path_log;
			fileHandler::xmlToDisk($SCobject->__getLastRequest(), time() . "_req_" . $filename . ".xml", $path);
			fileHandler::xmlToDisk($SCobject->__getLastResponse(), time() . "_resp_" . $filename . ".xml", $path);
		}
	}
	
	/********
	* Dump object to file
	*********/
	private function logObject ($filename, $object, $path = false) {
		if ($this->debugMode) {
			if(!$path) $path = self::__default_path_log;
			filehandler::jsonToDisk($object, time() . "_" . $filename . ".json", $path);
		}
	}
	
	/*********
	* Creates SOAP client with options
	**********/

	private function createSC ($url) {
		return new SoapClient($url, $this->soapOptions());
	}
	/*********
	* Adapts SOAP options from constructor call
	**********/
	private function soapOptions() {
		$array = self::__soap_options;
		$array['exceptions'] = $this->useExceptions;
		$array['trace'] = !$this->useExceptions;
		return $array;
	}
	/*********
	* Saves labels to disk
	**********/
	public function labelsToDisk($path = false, $filename = false) {
		if (property_exists($this->shipment, 'labels')) {
			if(!$path) $path = self::__default_path_log;
			if (!$filename) $filename = time() . "_label";
			foreach($this->shipment->labels as $format => $label) {
				filehandler::b64ToDisk($label, $filename . "." . $format, self::__default_path_log);
			}
		}
	}
	public function __destruct(){
	}
}
?>