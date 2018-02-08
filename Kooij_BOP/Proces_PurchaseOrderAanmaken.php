<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<?php include '../Tools/proces_init.php';?>
    <head>
        <title>Aanmaken van een purchase order voor Kooij B.V.</title>
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
			$proces->includeActiviteit(1, 0, 0,"Activiteit_Select_SemiFinishedProduct.php");
			
			// Tweede activiteit
			$proces->includeActiviteit(2, 0, 0, "Activiteit_Retrieve_Supplier.php");

			// Derde activiteit
			$proces->includeActiviteit(3, 0, 0, "Activiteit_Create_Purchase_Order.php");

			// Beslissing
			$proces->includeBeslissing(4, 0, "Beslissing_PO_Opgeslagen.php", 5, 6);
				
			// Eerste pad, vierde activiteit
			$proces->includeActiviteit(5, 1, 1, "Activiteit_Show_PO_Opgeslagen.php");

			// Tweede pad, vijfde activiteit
			$proces->includeActiviteit(6, 2, 2, "Activiteit_Show_PO_Fout.php");
			
			// Einde van de flow.
			$proces->includeEind(7);
			
			echo '<Form action="Control_Centrum.php" method="POST" class="proces_box selectie">';
			echo 'Proces onderbreken/afronden<br>';
			echo '<Input type="submit" value="Stop">';
			echo '</Form>';
     	}		
		?>
	</body>
</html>
