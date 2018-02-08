<?php
	$ordersInProductie = $this->getMessagemapValue("orders_in_productie","TotalOrders"
		, "oip","http://localhost/Hanze_BOP/Kooij_BOP/OrdersInProductie");
	$comment="Kan de order geaccepteerd worden? Aantal orders($ordersInProductie)&lt 6";
	$branch_1=($ordersInProductie<6);
	$branch_2=true;
?>
