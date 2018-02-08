<?php
// **********************************************
// Webservice Demo by Elmü (www.netcult.ch/elmue)
// **********************************************
?>
<html>
<head>
<title>WebService Client Demo</title>
</head>
<style>
h1      { color:#000066; margin-bottom:5px; }
.TxtBox { width:180px; }
</style>
<body>

<h1>Math Calculation Webservice</h1>
<b><font color=green>This demo connects to localhost</font></b><br>

<Form action="Client_Math.php" method="POST">
	Value 1:
	<br><input type="text" class="TxtBox" name="Value1" value="21">
	<br>Value 2:
	<br> <input type="text" class="TxtBox" name="Value2" value="3">

	<br>Operation:
	<br><Select class="TxtBox" name="Operation">
		<Option value="Multiply">Multiply</Option>
		<Option value="Add">Add</Option>
		<Option value="Concat">Concat</Option>
	</Select>
	<Input type="submit" value="Execute">
	<br><input type="checkbox" name="Debug" id="Debug4" checked> <label for="Debug4">Print Debug Output</label>
</Form>

<h1>Soap String Transformation Webservice</h1>
<b><font color=green>This demo connects to localhost</font></b><br>
	
<Form action="Client_Soap.php" method="POST">
	Action:<br>
	<Select class="TxtBox" name="Action">
		<Option value="STR_RevertRQ">Revert String</Option>
		<Option value="STR_UpperRQ">Make Uppercase</Option>
		<Option value="STR_LowerRQ">Make Lowercase</Option>
	</Select>
	<br>Test String:
	<br><input type="text" class="TxtBox" name="Message" value="This is a little Text">
	<Input type="submit" value="Execute">
	<br><input type="checkbox" name="Debug" id="Debug5" checked> <label for="Debug5">Print Debug Output</label>
</Form>

<h1>Soap Webservice Tester</h1>
<b><font color=green>This demo connects to localhost</font></b><br>
	
<Form action="Tester.php" method="POST">
	Action:<br>
	<input class="text" class="TxtBox" name="Action" value="Naam van de webservice">
	<br>Element naam:
	<br><input type="text" class="TxtBox" name="Element" value="elementName">
	<br>Eerste parameter:
	<br>Naam:<input type="text" class="TxtBox" name="Param1" value="Parameter_1">
	<br>Waarde:<input type="text" class="TxtBox" name="Value1" value="Waarde eerste parameter">	
	<br>Tweede parameter:
	<br>Naam:<input type="text" class="TxtBox" name="Param2" value="Parameter_2">
	<br>Waarde:<input type="text" class="TxtBox" name="Value2" value="Waarde tweede parameter">
	<br><br>Server die aangeroepen wordt:
	<br><Select class="TxtBox" name="Server">
		<Option value="http://localhost:8080/Hanze_BOP/Meijer_BOP/Server_Meijer.php">Meijer</Option>
		<Option value="http://localhost:8080/Hanze_BOP/Kooij_BOP/Server_Kooij.php">Kooij</Option>
		<Option value="http://localhost:8080/Hanze_BOP/MSAccess/Server_MSAccess.php">MS-Access</Option>
		<Option value="http://localhost:8080/Hanze_BOP/MSSql/Server_MSSql.php">MS SQL</Option>
	</Select>
	<Input type="submit" value="Execute">
	<br><input type="checkbox" name="Debug" id="Debug5" checked> <label for="Debug5">Print Debug Output</label>
</Form>
<b><a href="../index.php">Back to Startpage</a></b>
</html>
</body>
</html>
