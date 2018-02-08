<?php
	$action="Retrieve_Stock";
	$activiteit = new Activiteit($action, $this->locatie, WEB_SERVICE);
	$activiteit->serverURL=Utils::GetBaseUrl()."Server_Meijer.php";
	$activiteit->serviceName="getVoorraad";
	$activiteit->elementName ="voorraad";
	$activiteit->namespace = "voo";
	$activiteit->nsURI = "http://localhost/Hanze_BOP/Kooij_BOP/voorraad";
	$activiteit->message ="<ProductID>1</ProductID>";
	$activiteit->naam="Haal voorraad op";
	$activiteit->completedTekst="Voorraadgegevens opgehaald";
	$activiteit->button="Bepaal voorraad";
?>
