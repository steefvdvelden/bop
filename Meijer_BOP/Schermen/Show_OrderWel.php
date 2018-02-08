<?php
$formOutput = new FormOutput($this, "Order Acceptatie");
$formOutput->exitButtons = FormOutput::OK_ONLY;
$ordersInProductie = $this->getMessagemapValue("orders_in_productie","TotalOrders"
		, "oip","http://localhost/Hanze_BOP/Kooij_BOP/OrdersInProductie");
$message = "Order kan doorgaan er zijn $ordersInProductie orders in productie";
$formOutput->AddElement(FormOutput::MESSAGE,$message);
$formOutput->show();
?>
