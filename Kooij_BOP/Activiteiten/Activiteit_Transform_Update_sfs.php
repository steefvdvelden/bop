<?php
	// ************************************************************************************
	// ALGEMEEN, deze twee regels zijn verplicht
	// Action is een korte beschrijving van de actie die uitgevoerd gaat worden bijv "Sel_Order"
	$action="TrSfs";
	
	// Maak de activiteit aan met de locatie en het type activiteit (WEB_SERVICE, SHOW_FORM of SUB_PROCES)
	// Dus alleen de tekst WEB_SERVICE eventueel vervangen
	$activiteit = new Activiteit($action, $this->locatie, TRANSFORM);

	// ************************************************************************************
	// TRANSFORM velden, alleen nodig als het om een TRANSFORM gaat
	// De locatie van de server die de webservice afhandelt (altijd invullen)
	$activiteit->xslSheet=Utils::GetBaseUrl() . "TransformSheets/TransformUpdateSfs.xsl";
	
	// De naam van het root-element van de gegevens die terugkomen van de webservice
	// Het eigenlijke root element van de service is deze naam met een s aan het einde, dit om te faciliteren
	// dat er meerdere records worden teruggegeven (altijd invullen)
	$activiteit->elementName ="ProductLists";
	
	// De prefix die gebruikt moet worden voor de namespace (invullen als er gebruik wordt gemaakt van namespaces)
	$activiteit->nsURI = "http://localhost/Hanze_BOP/Kooij_BOP/SemiFinProds";
	$activiteit->elementOut = "ProductLists";
	$activiteit->nsURIOut = "http://localhost/Hanze_BOP/Kooij_BOP/UpProdList";
	
	// ************************************************************************************
	// Velden die in de proces-flow worden getoond, naam en button zijn verplicht.
	// De naam van de activiteit zoals die in de procesflow wordt getoond
	$activiteit->naam="Hoog in stock op";
	
	// De tekst die wordt getoond als de activiteit al is uitgevoerd.
	// Hier kan bijvoorbeeld de opgehaalde waarde worden getoond (zoals hier tevredenheid)
	// Dit veld is niet verplicht
	$activiteit->completedTekst="De gegevens zijn opgehoogd";
	
	// De tekst op de knop in de proces flow
	$activiteit->button="Transformeer ProductList";
	?>
	
