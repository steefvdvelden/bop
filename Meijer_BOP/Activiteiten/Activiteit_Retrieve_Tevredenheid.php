<?php
	$action="Retrieve_Sat";
	$activiteit = new Activiteit($action, $this->locatie, WEB_SERVICE);
	$activiteit->serverURL=Utils::GetBaseUrl()."Server_Meijer.php";
	$activiteit->serviceName="getTevredenheid";
	$activiteit->elementName ="tevredenheid";
	$activiteit->namespace = "tev";
	$activiteit->nsURI = "http://localhost/Hanze_BOP/Kooij_BOP/tevredenheid";
	$activiteit->naam="Haal tevredenheid op";
	$tevredenheid = $this->getMessagemapValue($activiteit->elementName,"gemiddelde"
											, $activiteit->namespace,$activiteit->nsURI);
	$activiteit->completedTekst="Het resultaat is ".$tevredenheid;
	$activiteit->button="Bepaal tevredenheid";
?>
