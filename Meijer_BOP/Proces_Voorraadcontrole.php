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
			
			// Eerste activiteit				
			$proces->includeActiviteit(1, 0, 0 ,"Activiteit_CheckVoorraad.php");

			// Beslissing 
			$proces->includeBeslissing(2, 0, "Beslissing_Check_Voorraad.php", 3, 4);
				
			// Eerste pad, tweede activiteit
			$proces->includeActiviteit(3, 1, 1, "Activiteit_Show_Voldoende.php");

			// Tweede pad, derde activiteit
			$proces->includeActiviteit(4, 2, 2, "Activiteit_Show_TeWeinig.php");
							
			// Einde van de flow.
			$proces->includeEind(5);
			
			echo '<Form action="Control_Centrum.php" method="POST" class="proces_box selectie">';
			echo 'Proces onderbreken<br>';
			echo '<Input type="submit" value="Stop">';
			echo '</Form>';
     	}		
		?>
	</body>
</html>
