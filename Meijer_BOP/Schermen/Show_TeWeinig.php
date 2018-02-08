<?php
$formOutput = new FormOutput($this, "Voorraad Controle");
$formOutput->exitButtons = FormOutput::OK_ONLY;
$product = $this->getMessagemapValue("voorraad","ProductID","voo","http://localhost/Hanze_BOP/Kooij_BOP/voorraad");
$message = "Onvoldoende voorraad. Product $product kan niet geproduceerd worden.";
$formOutput->AddElement(FormOutput::MESSAGE,$message);
$formOutput->show();
?>	
