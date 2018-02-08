<?php
	$action="Sub_PurchaseOrderAanmaken";
	$activiteit = new Activiteit($action, $this->locatie, SUB_PROCES);
	$activiteit->naam="Proces Aankooporder aanmaken";
	$activiteit->subProces = "../Kooij_BOP/Proces_PurchaseOrderAanmaken.php";
	$activiteit->button="Start Proces";
?>