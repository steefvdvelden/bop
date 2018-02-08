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
			$proces->includeActiviteit(1, 0, 0 ,"Activiteit_Ontvang_Order.php");
			
			$proces->includeBeslissing(2, 0, "Beslissing_Order_Opgehaald.php", 3, 4);

			// Eerste pad, eerste activiteit
			$proces->includeActiviteit(3, 1, 1, "Activiteit_Proces_Order_Acceptatie.php");
			// Tweede pad, eerste activiteit
			$proces->includeEmpty(4, 2, 2);
			
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
