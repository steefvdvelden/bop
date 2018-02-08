<?php
	$action="Retrieve_OIP";
	$activiteit = new Activiteit($action, $this->locatie, WEB_SERVICE);
	$activiteit->serverURL=Utils::GetBaseUrl()."Server_Meijer.php";
	$activiteit->serviceName="getOrdersInProductie";
	$activiteit->elementName ="orders_in_productie";
	$activiteit->namespace = "oip";
	$activiteit->nsURI = "http://localhost/Hanze_BOP/Kooij_BOP/OrdersInProductie";
	$activiteit->naam="Haal Orders in Productie op";
	$ordersInProductie = $this->getMessagemapValue($activiteit->elementName,"TotalOrders"
											,$activiteit->namespace,$activiteit->nsURI);
	$activiteit->completedTekst="Het resultaat is ".$ordersInProductie;
	$activiteit->button="Bepaal Orders in Productie";
?>
