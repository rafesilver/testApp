<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US"> 

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title> 
		Personnel Registration
	</title>


	<style>
		.error {color: #FF0000;}
	</style>

	<!-- Include CSS for different screen sizes -->
	<link rel="stylesheet" type="text/css" href="defaultstyle.css">
</head>

<body>

<?php
	
	require 'connectToDatabase.php';

	// Connect to Azure SQL Database
	$conn = ConnectToDabase();

	// Get data for expense categories
	$tsql="SELECT CATEGORY FROM Expense_Categories ORDER BY CATEGORY ASC";
	$expenseCategories= sqlsrv_query($conn, $tsql);

	// Populate dropdown menu options 
	$options = '';
	while($row = sqlsrv_fetch_array($expenseCategories)) {
		$options .="<option>" . $row['CATEGORY'] . "</option>";
	}

	// Close SQL database connection
	sqlsrv_close ($conn);

	// Get the session data from the previously selected Expense Month, if it exists
	session_start();
	if ( !empty( $_SESSION['prevSelections'] ))
	{ 
		$prevSelections = $_SESSION['prevSelections'];
		unset ( $_SESSION['prevSelections'] );
	}

	// Extract previously-selected Month and Year
	$prevStartDate= $prevSelections['prevStartDate'];
	$prevEndDate= $prevSelections['prevEndDate'];
?>

<div class="intro">

	<h2> Personnel Registration </h2>

	<!-- Display redundant error message on top of webpage if there is an error -->
	<h3> <span class="error"> <?php echo $prevSelections['errorMessage'] ?> </span> </h3>

</div>

<!-- Define web form. 
The array $_POST is populated after the HTTP POST method.
The PHP script insertToDb.php will be executed after the user clicks "Submit"-->
<div class="container">
	<form action="insertToDb.php" method="post">

		<label>Start Date:</label>
		<input type="text" step="1" name="start_date" required>

		<label>End Date:</label>
		<input type="text" step="1" name="end_date" required>

		<label>Vehicle Make:</label>
		<input type="text" step="1" name="vehicle_make" required>

		<label>Vehicle Model:</label>
		<input type="text" step="1" name="vehicle_model" required>

		<label>Employee Name:</label>
		<input type="text" step="1" name="employee_name" required>

		<!-- Dropdown menu for expense month, remembering previously selected month -->
		<!-- <label>Expense Month</label>
		<select name="expense_month">
			<option value="-1">Month:</option>
			<option value="01"<?php //echo $prevExpenseMonth == 1 ? 'selected="selected"' : ''; ?>>Jan</option>
			<option value="02"<?php //echo $prevExpenseMonth == 2 ? 'selected="selected"' : ''; ?>>Feb</option>
			<option value="03"<?php //echo $prevExpenseMonth == 3 ? 'selected="selected"' : ''; ?>>Mar</option>
			<option value="04"<?php //echo $prevExpenseMonth == 4 ? 'selected="selected"' : ''; ?>>Apr</option>
			<option value="05"<?php //echo $prevExpenseMonth == 5 ? 'selected="selected"' : ''; ?>>May</option>
			<option value="06"<?php //echo $prevExpenseMonth == 6 ? 'selected="selected"' : ''; ?>>Jun</option>
			<option value="07"<?php //echo $prevExpenseMonth == 7 ? 'selected="selected"' : ''; ?>>Jul</option>
			<option value="08"<?php //echo $prevExpenseMonth == 8 ? 'selected="selected"' : ''; ?>>Aug</option>
			<option value="09"<?php //echo $prevExpenseMonth == 9 ? 'selected="selected"' : ''; ?>>Sep</option>
			<option value="10"<?php //echo $prevExpenseMonth == 10 ? 'selected="selected"' : ''; ?>>Oct</option>
			<option value="11"<?php //echo $prevExpenseMonth == 11 ? 'selected="selected"' : ''; ?>>Nov</option>
			<option value="12"<?php //echo $prevExpenseMonth == 12 ? 'selected="selected"' : ''; ?>>Dec</option>
		</select><br> -->

		<!-- Text input for year, remembering previously selected year -->
		<!-- <label>Expense Year:</label>
		<input type="number" step="1" name="expense_year" value="<?php echo $prevExpenseYear;  ?>" required><br> -->
 
		<!-- <label>Expense Amount (US$):</label>
		<input type="number" step="0.01" name="expense_amount" required><br> -->

		<!-- <label>Expense Category:</label>
		<select name="expense_category">
			<option value="-1">Category:</option>
			<?php //echo " . $options . " ?>
		</select><br> -->

		<!-- <label>Notes (optional) [no accents or tildes]:</label>
		<input type="text" name="input_note" ><br> -->

		<button type="submit">Submit</button>
	</form>
</div>

<h3> Previous Input (if any) - for verification purposes:</h3>
<p> Start Date: <?php echo $prevSelections['prevStartDate'] ?> </p>
<p> End Date: <?php echo $prevSelections['prevEndDate'] ?> </p>
<p> Vehicle Make: <?php echo $prevSelections['prevVehicleMake'] ?> </p>
<p> Vehicle Model: <?php echo $prevSelections['prevVehicleModel'] ?> </p>
<p> Employee Name: <?php echo $prevSelections['prevEmployeeName'] ?> </p>
<p> <span class="error"> <?php echo $prevSelections['errorMessage'] ?> </span> </p>

</body>
</html>
