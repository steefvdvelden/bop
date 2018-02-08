<?php 
	$action="Show_OrderAccepted";
	$activiteit = new Activiteit($action, $this->locatie, SHOW_FORM);
	$activiteit->formNaam="Show_OrderWel.php";
	$activiteit->naam="Toon Order geaccepteerd";
	$activiteit->button="Toon Scherm";
?>
