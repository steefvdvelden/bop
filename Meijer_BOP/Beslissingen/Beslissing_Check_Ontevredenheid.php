<?php
	$tevredenheid = $this->getMessagemapValue("tevredenheid","gemiddelde","tev","http://localhost/Hanze_BOP/Kooij_BOP/tevredenheid");
	$comment="Zijn de klanten ontevreden? Tevredenheid($tevredenheid)&lt 6";
	$branch_1=($tevredenheid<6);
	$branch_2=true;
?>
