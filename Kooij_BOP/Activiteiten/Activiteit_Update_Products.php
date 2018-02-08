<?php 
	$action="Update_SFP";
	$activiteit = new Activiteit($action, $this->locatie, WEB_SERVICE);
	$activiteit->serverURL=Utils::GetBaseUrl()."Server_Kooij.php";
	$activiteit->serviceName="updateProducts";
	$activiteit->elementName ="ProductList";
	$activiteit->namespace = "sfu";
	$activiteit->nsURI="http://localhost/Hanze_BOP/Kooij_BOP/UpProdList";
	$activiteit->rsInput=1;
	// Ophalen recordset uit de messagemap, omdat deze recordset wordt doorgegeven gebruikt de update dezelfde namespace 
	$xml = Utils::getDomDocument($this->Dom,
									 $activiteit->elementName."s",
									 $activiteit->nsURI);
	$message .= str_replace('"', "'",Utils::GetXML($xml));
	$activiteit->message =$message;
	$activiteit->naam="Update SemiFinishedProducts";
	$activiteit->button="Update products";
?>

