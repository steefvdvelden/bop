<?php
	$tevredenheid = $this->getMessagemapValue("tevredenheid","gemiddelde","tev","http://localhost/Hanze_BOP/Kooij_BOP/tevredenheid");
	$comment="Zijn de klanten tevreden? Tevredenheid($tevredenheid)&gt 6";
	$branch_1=($tevredenheid>=6);
	$branch_2=true;
?>
