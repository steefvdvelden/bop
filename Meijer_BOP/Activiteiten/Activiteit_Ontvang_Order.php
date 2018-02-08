<?php
	// ALGEMEEN
	// Action is een korte beschrijving van de actie die uitgevoerd gaat worden
	$action="Ret_Order";
	// Maak de activiteit aan met de locatie en het type activiteit (WEB_SERVICE, SHOW_FORM of SUB_PROCES)
	$activiteit = new Activiteit($action, $this->locatie, WEB_SERVICE);

	// WEB_SERVICE velden
	// De locatie van de server die de webservice afhandelt
	$activiteit->serverURL="http://localhost:8080/Hanze_BOP/Kooij_BOP/Server_Kooij.php";
	// De naam van de webservice die wordt aangeroepen
	$activiteit->serviceName="getNewOrder";
	// De naam van het root-element van de gegevens die terugkomen van de webservice
	$activiteit->elementName ="kooijOrder";
	// De input voor de webservice, in XML formaat
	$activiteit->message ="";
	
	// Velden die in de proces-flow worden getoond
	// De naam van de activiteit zoals die in de procesflow wrodt getoond
	$activiteit->naam="Haal Order bij Kooij";
	// De tekst die wordt getoond als de activiteit al is uitgevoerd.
	// Hier kan bijvoorbeeld de opgehaalde waarde worden getoond
	$orderNummer = $this->getMessagemapValue($activiteit->elementName,"Ordernumber");
	$activiteit->completedTekst="Order nummer $orderNummer is opgehaald.";
	// De tekst op de knop in de proces flow
	$activiteit->button="Haal Order";
	?>
	