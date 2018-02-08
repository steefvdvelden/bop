<?php
	$action="Retrieve_Sup";
	$activiteit = new Activiteit($action, $this->locatie, WEB_SERVICE);
	$activiteit->serverURL=Utils::GetBaseUrl()."Server_Kooij.php";
	$activiteit->serviceName ="getSupplierByID";
	$activiteit->elementName ="supplier";
	$activiteit->namespace = "sup";
	$activiteit->nsURI = "http://localhost/Hanze_BOP/Kooij_BOP/Supplier";
	$supplierID = $this->getMessagemapValue("SemiFinishedProduct","SupplierID", "sfp","http://localhost/Hanze_BOP/Kooij_BOP/SemiFinProd");
	$activiteit->message ="<supId>$supplierID</supId>";
	$activiteit->naam="Ophalen Leverancier gegevens";
	$supplierName=$this->getMessagemapValue("supplier","CompanyName", 
											$activiteit->namespace,$activiteit->nsURI);
	$activiteit->completedTekst="De geselecteerde leverancier is ".$supplierName;
	$activiteit->button="Ophalen Leverancier";
?>
