<?php
	$formOutput = new FormOutput($this, "Selectie Contact");
	$formOutput->exitButtons = FormOutput::NO_BUTTONS;
    $formOutput->nameSpace = "con";
    $formOutput->nsURI = "http://localhost/Hanze_BOP/MSAccess/Contact";
    $formOutput->elementName ="contacten";
	
    $selectForm = new SelectForm();
    $selectForm->source = SelectForm::MESSAGEMAP;
    $selectForm->messageMapElement = "relatie";
    $selectForm->messageMapNS="http://localhost/Hanze_BOP/MSAccess/relatie";
    $formOutput->AddElement(FormOutput::SELECT_FORM,$selectForm);
    
    $formOutput->show();
?>
