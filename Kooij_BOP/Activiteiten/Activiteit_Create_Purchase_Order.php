<?php 
	$action="Create_PO";
	$activiteit = new Activiteit($action, $this->locatie, WEB_SERVICE);
	$activiteit->serverURL=Utils::GetBaseUrl()."Server_Kooij.php";
	$activiteit->serviceName="insertPurchaseOrder";
	$activiteit->elementName ="PurchaseOrderRecord";
	$activiteit->namespace = "ipo";
	$activiteit->nsURI="http://localhost/Hanze_BOP/Kooij_BOP/CreatePO";
	// Supplier locatie in de messagemap
	$nsURIsup="http://localhost/Hanze_BOP/Kooij_BOP/Supplier";
	$namespaceSup="sup";
	// SemiFinishedProduct locatie in de messagemap
	$nsURIsfp="http://localhost/Hanze_BOP/Kooij_BOP/SemiFinProd";
	$namespaceSfp="sfp";
	$message="";
		$message ='<Supplier>'.$this->getMessagemapValue("supplier","CompanyName",$namespaceSup,$nsURIsup).'</Supplier>';
		$message .='<Address>'.$this->getMessagemapValue("supplier","Address",$namespaceSup,$nsURIsup).'</Address>';
		$message .='<Postalcode>'.$this->getMessagemapValue("supplier","Postalcode",$namespaceSup,$nsURIsup).'</Postalcode>';
		$message .='<City>'.$this->getMessagemapValue("supplier","City",$namespaceSup,$nsURIsup).'</City>';
		$message .='<Product>'.$this->getMessagemapValue("SemiFinishedProduct","Name",$namespaceSfp,$nsURIsfp).'</Product>';
		$message .='<Amount>'.$this->getMessagemapValue("SemiFinishedProduct","Amount",$namespaceSfp,$nsURIsfp).'</Amount>';
// ReceievedDate moet leeg zijn, order is immer nog niet geleverd.
//		$message .='<ReceivedDate>'.date("Y-m-d H:i:s").'</ReceivedDate>';
		$message .='<ReceivedDate>0</ReceivedDate>';
		$message .='<Price_per_unit>'.$this->getMessagemapValue("SemiFinishedProduct","Price",$namespaceSfp,$nsURIsfp).'</Price_per_unit>';
	$activiteit->message =$message;
	$activiteit->rsInput=1;
	$activiteit->naam="Opvoeren Aankoop Order";
	$activiteit->button="Opvoeren Order";
?>

