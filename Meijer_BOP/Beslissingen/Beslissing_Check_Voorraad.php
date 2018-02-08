<?php
	$inStock = $this->getMessagemapValue("voorraad","RawMaterialInStock","voo","http://localhost/Hanze_BOP/Kooij_BOP/voorraad");
	$needed = $this->getMessagemapValue("voorraad","NumberOfRawMaterial_PP","voo","http://localhost/Hanze_BOP/Kooij_BOP/voorraad");
	$comment="Is er genoeg voorraad? Voorraad($inStock)&gt= nodig($needed)";
	$branch_1=($inStock>=$needed);
	$branch_2=true;
?>
