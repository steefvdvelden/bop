<?php 
	$action="Sel_CON";
	$activiteit = new Activiteit($action, $this->locatie, SHOW_FORM);
	$activiteit->formNaam="Show_Select_Contact.php";
	$activiteit->naam="Selecteer Contact";
	$Name=$this->getMessagemapValue("contacten","companyName"
											, "con","http://localhost/Hanze_BOP/MSAccess/Contact");
	$activiteit->completedTekst="Het geselecteerde contact is ".$Name;
	$activiteit->button="Selecteer Contact";
?>