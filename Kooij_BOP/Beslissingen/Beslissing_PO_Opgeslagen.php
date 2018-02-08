<?php
	$result_PO=$this->getMessagemapValue("PurchaseOrderRecord","Inserted_record_ID","ipo","http://localhost/Hanze_BOP/Kooij_BOP/CreatePO");
	$comment="Is de order succesvol opgeslagen? Resultaat($result_PO)&gt 0";
	$branch_1=($result_PO>0);
	$branch_2=true;
?>