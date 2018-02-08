<?php
	$action="Sub_OrderAcceptatie";
	$activiteit = new Activiteit($action, $this->locatie, SUB_PROCES);
	$activiteit->naam="Proces Order Acceptatie";
	$activiteit->subProces = "Proces_Check_Customer_Satisfaction.php";
//	$activiteit->subProces = "Proces_OrderAcceptatie.php";
	$activiteit->button="Start Proces";
	?>
	