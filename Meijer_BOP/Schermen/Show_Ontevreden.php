<?php
	$formOutput = new FormOutput($this, "Klant Tevredenheid");
	$formOutput->exitButtons = FormOutput::OK_ONLY;
	$tevredenheid = $this->getMessagemapValue("tevredenheid","gemiddelde","tev","http://localhost/Hanze_BOP/Kooij_BOP/tevredenheid");
	$message = "De klanten zijn heel ontevreden, ze waarderen de service met een $tevredenheid";
	$formOutput->AddElement(FormOutput::MESSAGE,$message);
	$formOutput->show();
?>
