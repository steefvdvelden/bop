<?php
	$orderNumber = $this->getMessagemapValue("kooijOrder","Ordernumber");
	$comment="Is een order opgehaald? Ordernummer($orderNumber)&gt 0";
	$branch_1=($orderNumber>0);
	$branch_2=true;
?>
