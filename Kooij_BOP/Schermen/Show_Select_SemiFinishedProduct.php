<?php
	$formOutput = new FormOutput($this, "Selectie half-product");
	$formOutput->exitButtons = FormOutput::NO_BUTTONS;
    $formOutput->nameSpace = "sfp";
    $formOutput->nsURI = "http://localhost/Hanze_BOP/Kooij_BOP/SemiFinProd";
    $formOutput->elementName ="SemiFinishedProduct";
	
    $selectForm = new SelectForm();
    $selectForm->source = SelectForm::WEB_SERVICE;
    $selectForm->serverURL = Utils::GetBaseUrl() . "Server_Kooij.php";
	$selectForm->getWebService = "getSemiFinishedProducts";
    $selectForm->selectValues="";
    $selectForm->extraType = SelectForm::SELECT_LIST;
    $selectForm->extraField = "Amount";
    $selectForm->extraValue = array(1,10,15,20,100);
    $formOutput->AddElement(FormOutput::SELECT_FORM,$selectForm);
    
    $formOutput->show();
?>
