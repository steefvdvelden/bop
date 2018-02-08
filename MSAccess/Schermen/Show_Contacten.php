<?php
	$formOutput = new FormOutput($this, "Contacten uit de database");
	$formOutput->exitButtons = FormOutput::OK_ONLY;
	
	$tableForm = new TableForm();
	$tableForm->messageMapElement="relatie";
	$tableForm->messageMapNS="http://localhost/Hanze_BOP/MSAccess/relatie";
	$formOutput->AddElement(FormOutput::TABLE, $tableForm);
	$formOutput->show();
?>