<?php
$formResult ="";
$namespace="";
$namespaceElement = "";
foreach ($_POST as $key => $value) {
	switch ($key)
	{
		case "Destination":
			$destination = $value;
			break;
		case "MessageMap":
			$messageMapIn = $value;
			break;
		case "ElementName":
			$elementName = $value;
			break;
		case "Step":
			$stap=$value;
			break;
		case "Join":
			$join=$value;
			break;
		case "Pad2":
			$pad2=$value;
			break;
		case "StartInput":
			$startInput=$value;
			break;
		case "Action":
			break;
		case "FormName":
			break;
		case "Automatisch":
			$automatisch=$value;
			break;
		case "NameSpace":
			$namespace=$value;
			$namespaceElement = $namespace.":";
			break;
		case "nsURI":
			$nsURI=$value;
			break;		
		case "Selection":
			$formResult.=$value;
			break;
		default:
			$formResult.="<$key>$value</$key>";
	}
}
	if ($namespace!=""){
		$rootElement = "<$namespace:$elementName xmlns:$namespace='$nsURI'>";
		$rootClose = "</$namespace:$elementName>";
	}
	else{
		$rootElement = "<$elementName>";
		$rootClose = "</$elementName>";
	}
	$messageMap=$messageMapIn."$rootElement$formResult$rootClose";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<body>
		<?php 
			echo '<Form id="TheForm" action="'.$destination.'" method="POST">';
			echo '<input type="hidden" name="MessageMap" value="'.$messageMap.'">';
			echo '<input type="hidden" name="Step" value="'.$stap.'">';
			echo '<input type="hidden" name="Join" value="'.$join.'">';
			echo '<input type="hidden" name="Pad2" value="'.$pad2.'">';
			echo '<input type="hidden" name="StartInput" value="'.$startInput.'">';
			echo '<input type="hidden" name="Automatisch" value="'.$automatisch.'">';
			echo '<Input type="submit" value="Process">'; 
		?>
		</Form>

	  		<script type="text/javascript">document.getElementById("TheForm").submit();</script>
	</body>
</html>