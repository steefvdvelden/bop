<?php
	// Haal het(de) gegeven(s) uit de messagemap op die gecontroleerd moeten worden 
	$result_PO=$this->getMessagemapValue("PurchaseOrderRecord","Inserted_record_ID","ipo","http://localhost/Hanze_BOP/Kooij_BOP/CreatePO");
	
	// Het commentaar naast de beslissing in de flow
	$comment="Is de order succesvol opgeslagen? Resultaat($result_PO)&gt 0";
	
	// De conditie waaraan voldaan moet worden om het linker pad te nemen
	$branch_1=($result_PO>0);
?>