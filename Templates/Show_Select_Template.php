<?php
	// Een selectie formulier toont een aantal records waaruit één record geselecteerd kan worden.
	// De gegevens die getoond worden kunnen opgehaald worden met behulp van een webservice call,
	// of uit de messagemap gehaald worden. Dit wordt aangegeven vmet de source variabele.
    // Aanmaken van een SelectForm object en het FormOutput object.
	$formOutput = new FormOutput($this, "Selectie half-product");
	$formOutput->exitButtons = FormOutput::NO_BUTTONS;
    // Gegevens voor het opslaan van het resultaat in de messagemap, hier wordt ook elementName weer gebruikt
    // Prefix van de namespace van het geselecteerde gegeven in de messagemap
    $formOutput->nameSpace = "sfp";
    // De URI die de namespace identificeert
    $formOutput->nsURI = "http://localhost/Hanze_BOP/Kooij_BOP/SemiFinProd";
    // Naam van de gegevens groep (XML root tag)
    $formOutput->elementName ="SemiFinishedProduct";
	
    $selectForm = new SelectForm();
    
    // Bron van de gegevens WEB_SERVICE of MESSAGEMAP
    $selectForm->source = SelectForm::WEB_SERVICE;
    
    // Het element uit de messagemap, alleen invullen bij MESSAGEMAP
    $selectForm->messageMapElement = "elementNaam";
    // De URI die de namespace identificeert in de messagemap
    $selectForm->messageMapNS = "http://localhost/Hanze_BOP/etc..";
    
    // De gegevens van de webservice die gebruikt wordt om de gegevens waaruit geselecteerd 
    // moet worden ophaalt. Alleen invullen als het om een WEB_SERVICE formulier gaat.
    // Server locatie
    $selectForm->serverURL = Utils::GetBaseUrl() . "Server_Kooij.php";
    // Naam van de webservice
	$selectForm->getWebService = "getSemiFinishedProducts";
    // Selectie waarde die wordt meegegeven bij het ophalen van de gegevens (voorselectie)
    // Dit moet een (lijst van) XML element(en) zijn bijvoorbeeld "<prodID>waarde</prodID>"
    $selectForm->selectValues="";
        
    // De gegevens voor de extra kolom die getoond wordt in het selectie formulier
    // Type invoerveld SELECT_LIST of SINGLE_INPUT
    $selectForm->extraType = SelectForm::SELECT_LIST;
    // Naam van het extra veld
    $selectForm->extraField = "Amount";
    // Gegevens die geselecteerd kunnen worden (bij een list), standaard waarde bij een SINGLE_INPUT
    $selectForm->extraValue = array(1,10,15,20,100);
    // of
    // $selectForm->extraValue = "waarde";
    
    // Toevoegen aan de formoutput en tonen.
    $formOutput->AddElement(FormOutput::SELECT_FORM,$selectForm);
    
    $formOutput->show();
?>