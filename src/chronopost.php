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
	
	public $shippingSC;
	private $debugMode;
	private $useExceptions;

	public function __construct($useExceptions = false, $debug = false) {
		$this->debugMode = $debug;
		$this->useExceptions = $useExceptions;
		$this->shipment = new shipment($this->useExceptions);
		// $this->tracking = new tracking($this->useExceptions);
	}
	
	public function makeShippingLabels ($labelsdata, $automaticLabelRecovery = true, $twoWays = false) {
		
		//Soap dataset forging
		
		$this->shipment->loadArray($labelsdata);
		if ($this->debugMode) filehandler::jsonToDisk($this->shipment, time() . "shipping_obj_init" . ".json", self::__default_path_log);
		
		//Makes it 2 ways
		if ($twoWays) {
			$this->shipment->makeTwoWays();
			if ($this->debugMode) filehandler::jsonToDisk($this->shipment, time() . "shipping_obj_2ways" . ".json", self::__default_path_log);
		}
		
		//Force reservation to get a common way for labels recovery
		$this->shipment->skybillParamsValue->forceReservation();
		
		//Makes call if Ready For Launch
		if ($this->shipment->RFLcheck()) {
			
			if (!is_object($this->shippingSC)) $this->shippingSC = $this->createSC(self::__shipping_wsdl);
			$response = $this->shippingSC->shippingMultiParcelV4($this->shipment);
			if ($this->debugMode) $this->logLastRq("shipping", $this->shippingSC);
			
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
			if ($this->debugMode) filehandler::jsonToDisk($this->shipment, time() . "shipping_obj_withResponse" . ".json", self::__default_path_log);
			//Labels recorevy
			if ($automaticLabelRecovery) $this->getReservedLabels();
		}
		else {
			if ($this->useExceptions) throw new wsexception (__METHOD__ . " shippingMultiparcelV4 failed, error code $response->return->errorCode | $response->return->errorMessage");
			return false;
		}
		
		return true;
	}
	
	public function getReservedLabels() {
		$labels = new wslabelrecoveryvalue();
		if (!is_object($this->shippingSC)) $this->shippingSC = $this->createSC(self::__shipping_wsdl);
		
		$labels->setreservationNumber($this->shipment->reservationNumber);
		foreach($this->shipment->skybillParamsValue->getModesArray() as $mode) {
			$labels->setmode($mode);
			if ($labels->RFLcheck()) {
				if ($this->debugMode) filehandler::jsonToDisk($labels, time() . "labels_obj_" . $mode . ".json", self::__default_path_log);
				$response = $this->shippingSC->getReservedSkybillWithTypeAndMode($labels);
				if ($this->debugMode) $this->logLastRq("getlabels_" . $mode, $this->shippingSC);
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
			}
		}
	}
	
	public function eraseLabel() {
		
	}
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
	
	private function logLastRq ($filename, $SCobject, $path = false) {
		if(!$path) $path = self::__default_path_log;
		if ($this->debugMode) {
			fileHandler::xmlToDisk($SCobject->__getLastRequest(), time() . "req_" . $filename . ".xml", $path);
			fileHandler::xmlToDisk($SCobject->__getLastResponse(), time() . "resp_" . $filename . ".xml", $path);
		}
	}
	
	private function differentLabels() {
		$this->LabelsArray['Formats'] = $this->shipment->skybillParamsValue->getModesArray();
	}
	private function createSC ($url) {
		return new SoapClient($url, $this->soapOptions());
	}
	private function soapOptions() {
		$array = self::__soap_options;
		$array['exceptions'] = $this->useExceptions;
		$array['trace'] = !$this->useExceptions;
		return $array;
	}
	public function labelsToDisk() {
		if (property_exists($this->shipment, 'labels')) {
			foreach($this->shipment->labels as $format => $label) {
				filehandler::b64ToDisk($label, time() . "_label." . $format, self::__default_path_log);
			}
		}
	}
	public function __destruct(){
	}
}
?>