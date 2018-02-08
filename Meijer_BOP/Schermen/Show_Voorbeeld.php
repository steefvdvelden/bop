<?php
	$formOutput = new FormOutput($this, "Voorbeeldscherm");
	$formOutput->exitButtons = FormOutput::YES_NO;
	$formOutput->elementName = "invoertest";
	$formOutput->nameSpace = "inv";
	$formOutput->nsURI = "http://localhost/Hanze_BOP/Kooij_BOP/invoertest";
	
	// Tonen van een message
	$tevredenheid = $this->getMessagemapValue("tevredenheid","gemiddelde","tev","http://localhost/Hanze_BOP/Kooij_BOP/tevredenheid");
	$message = "De klanten zijn heel tevreden, ze waarderen de service met een $tevredenheid";
	$formOutput->AddElement(FormOutput::MESSAGE,$message);
	
	// Tonen van een aantal invoer velden
	$inputElement = new InputElements();
	$inputElement->inputBoxes = array("Voornaam"=>"Piet", "Achternaam"=>"Puk", "Adres"=>"");
	$formOutput->AddElement(FormOutput::INPUT_BOX, $inputElement);
	
	// Toon het scherm
	$formOutput->show();
?>