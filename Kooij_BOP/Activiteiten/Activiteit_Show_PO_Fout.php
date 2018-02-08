<?php 
	$action="Show_FOUT";
	$activiteit = new Activiteit($action, $this->locatie, SHOW_FORM);
	$activiteit->formNaam="Show_PurchaseOrder_Fout.php";
	$activiteit->naam="Toon foutmelding";
	$activiteit->button="Toon Scherm";
?>
