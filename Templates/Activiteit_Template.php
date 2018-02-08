<?php
	// ************************************************************************************
	// ALGEMEEN, deze twee regels zijn verplicht
	// Action is een korte beschrijving van de actie die uitgevoerd gaat worden bijv "Sel_Order"
	$action="Naam van de actie";
	
	// Maak de activiteit aan met de locatie en het type activiteit (WEB_SERVICE, SHOW_FORM of SUB_PROCES)
	// Dus alleen de tekst WEB_SERVICE eventueel vervangen
	$activiteit = new Activiteit($action, $this->locatie, WEB_SERVICE);

	// ************************************************************************************
	// WEB_SERVICE velden, alleen nodig als het om een WEB_SERVICE call gaat
	// De locatie van de server die de webservice afhandelt (altijd invullen)
	$activiteit->serverURL=Utils::GetBaseUrl()."Server_Meijer.php";
	
	// De naam van de webservice die wordt aangeroepen (altijd invullen)
	// Deze naam moet terug te vinden zijn in de Server file bij de afhandeling van de actie
	$activiteit->serviceName="getTevredenheid";
	
	// De naam van het root-element van de gegevens die terugkomen van de webservice
	// Het eigenlijke root element van de service is deze naam met een s aan het einde, dit om te faciliteren
	// dat er meerdere records worden teruggegeven (altijd invullen)
	$activiteit->elementName ="tevredenheid";
	
	// De prefix die gebruikt moet worden voor de namespace (invullen als er gebruik wordt gemaakt van namespaces)
	$activiteit->namespace = "tev";	
	
	// Het webadres dat gebruikt wordt om de namespace te identificeren
	// (invullen als er gebruik wordt gemaakt van namespaces)
	$activiteit->nsURI = "http://localhost/Hanze_BOP/Kooij_BOP/tevredenheid";
	
	// Worden er meerder records opgestuurd of niet
	// 0 = 1 record, 1 = meerdere records, bijvoorbeeld nij het updaten van een set records
	$activiteit->rsInput=0;
	
	// De input voor de webservice, in XML formaat dus bijvoorbeeld <ProductID>waarde</ProductID>
	// Deze gegevens komen in de server in de array $input, dit voorbeeld is te vinden met $input["ProductID"]
	$activiteit->message ="";
	
	// ************************************************************************************
	// SHOW_FORM velden, alleen invullen als het om een SHOW_FORM gaat
	// De naam van het php bestand van het scherm dat getoond moet worden
	$activiteit->formNaam="naam.php";
	
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
	// SUB_PROCES velden, alleen invullen als het om een SUB_PROCES gaat
	// Het php bestand met de flow van het sub-proces
	$activiteit->subProces = "../Kooij_BOP/Proces_PurchaseOrderAanmaken.php";
	
	// ************************************************************************************
	// Velden die in de proces-flow worden getoond, naam en button zijn verplicht.
	// De naam van de activiteit zoals die in de procesflow wordt getoond
	$activiteit->naam="Haal tevredenheid op";
	
	// De tekst die wordt getoond als de activiteit al is uitgevoerd.
	// Hier kan bijvoorbeeld de opgehaalde waarde worden getoond (zoals hier tevredenheid)
	// Dit veld is niet verplicht
	$tevredenheid = $this->getMessagemapValue($activiteit->elementName,"gemiddelde");
	$activiteit->completedTekst="Het resultaat is ".$tevredenheid;
	
	// De tekst op de knop in de proces flow
	$activiteit->button="Bepaal tevredenheid";
	?>
	