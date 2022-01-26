<?php
/********************
 *
 *	@file : fileHandler.php
 *	@author : La Drome laboratoire
 *	@version: 1.0.0
 *	@Date: 15/01/2022
 *	@license GPL
 *
 *	méthodes d'écriture sur disque de différents fichiers
 *
 *
 ********************/ 
namespace ladromelaboratoire\chronopostws\utils;
use DOMDocument;
 
 class fileHandler {
	 
	 public static function xmlToDisk($data, $filename = 'default.xml', $path = './') {
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		$dom->loadXML($data);
		$dom->formatOutput = TRUE;
		
		fileHandler::fileToDisk($dom->saveXml(), $filename, $path);
		unset($dom);
	 }
	 public static function fileToDisk($data, $filename = 'default.unknown', $path = './') {
		$file = fopen ($path.$filename, 'wb');
		fwrite($file, $data);
		fclose($file);
		unset($file);
	 }
	 public static function jsonToDisk($data, $filename = 'default.json', $path = './') {
		fileHandler::fileToDisk(json_encode($data, JSON_PRETTY_PRINT), $filename, $path);
	 }
	 public static function b64ToDisk($data, $filename = 'default.unknown', $path = './') {
		fileHandler::fileToDisk(base64_decode($data, true), $filename, $path);
	 }
 }