<?php
	$action="Retrieve_Rel";
	$activiteit = new Activiteit($action, $this->locatie, WEB_SERVICE);
	$activiteit->serverURL=Utils::GetBaseUrl()."Server_MSSql.php";
	$activiteit->serviceName="getRelaties";
	$activiteit->elementName ="relatie";
	$activiteit->namespace = "rel";
	$activiteit->nsURI = "http://localhost/Hanze_BOP/MSSql/relatie";
	$activiteit->naam="Haal Relaties op";
	$activiteit->completedTekst="De relaties zijn opgehaald.";
	$activiteit->button="Haal Relaties";
?>
