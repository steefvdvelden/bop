<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<?php include '../Tools/proces_init.php';?>
    <head>
    	<title>Toon contacten uit MS-Access database</title>
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
			$proces->includeActiviteit(1, 0, 0 ,"Activiteit_Retrieve_Contacts.php");

			// Eerste pad, tweede activiteit
			$proces->includeActiviteit(2, 0, 0, "Activiteit_Show_Contacts.php");
			
			$proces->includeActiviteit(3, 0, 0, "Activiteit_Select_Contact.php");

			// Einde van de flow.
			$proces->includeEind(4);
			
			echo '<Form action="Control_Centrum.php" method="POST" class="proces_box selectie">';
			echo 'Proces onderbreken<br>';
			echo '<Input type="submit" value="Stop">';
			echo '</Form>';
     	}		
		?>
	</body>
</html>
