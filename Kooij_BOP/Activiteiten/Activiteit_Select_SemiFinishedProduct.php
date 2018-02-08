<?php 
	$action="Sel_SFP";
	$activiteit = new Activiteit($action, $this->locatie, SHOW_FORM);
	$activiteit->formNaam="Show_Select_SemiFinishedProduct.php";
	$activiteit->naam="Selecteer Semi Finished Product";
	$productName=$this->getMessagemapValue("SemiFinishedProduct","Name"
											, "sfp","http://localhost/Hanze_BOP/Kooij_BOP/SemiFinProd");
	$activiteit->completedTekst="Het geselecteerde product is ".$productName;
	$activiteit->button="Selecteer Product";
?>