<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<?php include '../Tools/proces_init.php';?>
    <head>
    	<title>Controleer de klant tevredenheid</title>
    </head>
    <body>	
   	<?php 
	   	if ($procesConfig->formName!=""){
   			// Toon een scherm
	   		$proces->showForm();
   		}
		else{
			// Toon de flow
			$proces->includeStart(0);
			
			$proces->includeActiviteit(1, 0, 0 ,"Activiteit_Retrieve_Tevredenheid.php");
			// Beslissing, twee opties, om biede paden te kunnen belopen
//			$proces->includeBeslissing(2, 0, "Beslissing_Check_Tevredenheid.php", 4, 6);
			$proces->includeBeslissing(2, 0, "Beslissing_Check_Ontevredenheid.php", 4, 6);
				
			// Eerste activiteiten in beide paden
			$proces->includeActiviteit(3, 1, 1, "Activiteit_Show_Tevreden.php");
			$proces->includeActiviteit(5, 2, 2,"Activiteit_Show_Voorbeeld.php");
			
			// Tweede activiteit in pad 1 en jump naar laatste activiteit in pad 2
			$proces->includeActiviteit(4, 1, 1, "Activiteit_Show_Voorbeeld.php");
			$proces->includeJumpTo(6, 2, 2, 13);
				
			// Alleen pad 1 wordt nog getoond, de positie in de flow wordt dus 0
			$proces->includeActiviteit(7, 1, 0, "Activiteit_Show_Tevreden.php");

			// Nieuwe beslissing in pad 1, biede opties weer
			$proces->includeBeslissing(8, 0, "Beslissing_Check_Ontevredenheid.php", 10, 12);
//			$proces->includeBeslissing(8, 0, "Beslissing_Check_Tevredenheid.php", 10, 12);
				
			// Activiteiten op beide paden
			$proces->includeActiviteit(9, 1, 1, "Activiteit_Show_Tevreden.php");
			$proces->includeEmpty(11, 2, 2);
			// Gebruik includeEmpty om de paden te balanceren
			$proces->includeEmpty(10, 1, 1);
			$proces->includeActiviteit(12, 2, 2,"Activiteit_Show_Tevreden.php");
			
			// Alle paden komen hier samen
			$proces->includeActiviteit(13, 0, 0, "Activiteit_Transform_tevredenheid.php");
			
			$proces->includeActiviteit(14, 0, 0, "Activiteit_Proces_PurchaseOrderAanmaken.php");		
			
			$proces->includeActiviteit(15, 0, 0, "Activiteit_Retrieve_Tevredenheid.php");
				
			$proces->includeEind(16);

			echo '<Form action="Control_Centrum.php" method="POST" class="proces_box selectie">';
			echo 'Proces onderbreken<br>';
			echo '<Input type="submit" value="Stop">';
			echo '</Form>';
     	}		
		?>
	</body>
</html>
