<?php
	$action="Retrieve_Sup";
	$activiteit = new Activiteit($action, $this->locatie, WEB_SERVICE);
	$activiteit->serverURL=Utils::GetBaseUrl()."Server_Kooij.php";
	$activiteit->serviceName ="getSemiFinishedProducts";
	$activiteit->elementName ="ProductList";
	$activiteit->namespace = "sfs";
	$activiteit->nsURI = "http://localhost/Hanze_BOP/Kooij_BOP/SemiFinProds";
	$activiteit->message ="";
	$activiteit->naam="Ophalen SemiFinished Products";
	$activiteit->completedTekst="De productlijst is opgehaald";
	$activiteit->button="Ophalen SemiFinished Products";
?>
