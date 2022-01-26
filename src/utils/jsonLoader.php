<?php
/********************
 *
 *	@file : JsonLoader.php
 *	@author : La Drome laboratoire
 *	@version: 1.2.0
 *	@Date: 06/04/2020
 *	@license GPL
 *
 *	Classe de chargement d'un JSON en tableau
 *	Ajout de la capacité de charger le JSON en variable d'environnement
 *
 *
 ********************/ 
namespace ladromelaboratoire\chronopostws\utils;
 
 class jsonLoader {
	 private CONST __defaultError = null;
	 public $jsonArray = null;
	 public $error = false;
	 public $error_message = "";
	 
	 /*
	  * forward request to jsonGrabber
	  */
	 public function __construct ($file = null) {
		if (!($file === null)) $this->jsonGrabber($file);
	 }

	 /*
	  * load the file and return array or __defaultError
	  */	 
	 public function jsonGrabber($file = null) {
		 if(is_file($file)) {
			$json = file_get_contents($file, true);
			if(!($json === false)) {
				$json = json_decode($json, true);
				if(is_array($json)){
					$this->jsonArray = $json;
				}
				else {
					$this->error = true;
					$this->error_message = "Json decoding failed " . $file;
				}
			}
			else {
				$this->error = true;
				$this->error_message = "File not loaded " . $file;
			}
		 }
		 else {
			 $this->error = true;
			 $this->error_message = "Not a file " . $file;
		 }
		 if($this->error) error_log($this->error_message, 0);
	 }
	 
	 /*
	  * Push the array to environment variables
	  */
	public function push2env () {
		foreach($this->jsonArray as $key => $value) {
			if(is_string($key) && (is_string($value) || is_numeric($value) || is_bool($value))) putenv($key . "=" .(string)$value);
		}
	 }
	
	public function __destruct() {
	}
 }
?>