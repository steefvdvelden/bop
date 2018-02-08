<?php
	$formOutput = new FormOutput($this, "Opslaan Aankoop Order");
	$formOutput->exitButtons = FormOutput::OK_ONLY;
	
	$result=$this->getMessagemapValue("PurchaseOrderRecord","error","ipo","http://localhost/Hanze_BOP/Kooij_BOP/CreatePO");
	$message = "Er is een fout opgetreden bij het opslaan.".
				"<br>Bij het opslaan van de order is de volgende fout opgetreden:".
				"<br>$result";
	$formOutput->AddElement(FormOutput::MESSAGE, $message);
			
	$message = "<br>Controle gegevens (debug info)";
	$formOutput->AddElement(FormOutput::MESSAGE, $message);
	
	$tableForm = new TableForm();
	$tableForm->messageMapElement="SemiFinishedProduct";
	$tableForm->messageMapNS="http://localhost/Hanze_BOP/Kooij_BOP/SemiFinProd";
	$formOutput->AddElement(FormOutput::TABLE, $tableForm);
	
	$tableForm->messageMapElement="supplier";
	$tableForm->messageMapNS="http://localhost/Hanze_BOP/Kooij_BOP/Supplier";
	$formOutput->AddElement(FormOutput::TABLE, $tableForm);
	
	$tableForm->messageMapElement="PurchaseOrderRecord";
	$tableForm->messageMapNS="http://localhost/Hanze_BOP/Kooij_BOP/CreatePO";
	$formOutput->AddElement(FormOutput::TABLE, $tableForm);
	
	$formOutput->show();
?>