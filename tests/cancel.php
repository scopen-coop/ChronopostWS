<?php
require '../vendor/autoload.php';
define('__etiquette', './cancel.json');

use ladromelaboratoire\chronopostws\utils\jsonLoader;
use ladromelaboratoire\chronopostws\chronopost;

//confs loading
$loadEtiq = new jsonLoader(__etiquette);
$etiq = $loadEtiq->jsonArray;
unset($loadEtiq);

//useException, debugMode
$chrono = new chronopost(false, true);

//data, automaticlabelrecorevy, twoWays
//if not using the automatic recovery, you can access labels through $chrono->shipment->labels array
$etiquette = $chrono->cancelSkybill($etiq);

if ($etiquette) {
	echo "done, see log folders";
}
else {
	echo "something failed";
}
?>