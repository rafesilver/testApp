<?php

	// Define function to handle basic user input
	function parse_input($data) 
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	// Define function to check that inputted expense number has a maximum of 2 decimal places
	// function validateTwoDecimals($number)
	// {
	//    return (preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $number));
	// }
 
	// PHP script used to connect to backend Azure SQL database
	require 'ConnectToDatabase.php';

	// Start session for this particular PHP script execution.
	session_start();

	// Define ariables and set to empty values
	$startDate = $endDate = $vehicleMake = $vehicleModel = $employeeName = $errorMessage = NULL;

	// Get input variables
	$startDate= parse_input($_POST['start_date']);
	$endDate= parse_input($_POST['end_date']);
	// $startDate = $endDate = $vehicleMake = $vehicleModel = $employeeName = $errorMessage = NULL;
	$vehicleMake= parse_input($_POST['vehicle_make']);
	$vehicleModel= parse_input($_POST['vehicle_model']);
	$employeeName= parse_input($_POST['employee_name']);


	// Get the authentication claims stored in the Token Store after user logins using Azure Active Directory
	// $claims= json_decode($_SERVER['MS_CLIENT_PRINCIPAL'])->claims;
	// foreach($claims as $claim)
	// {		
	// 	if ( $claim->typ == "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress" )
	// 	{
	// 		$userEmail= $claim->val;
	// 		break;
	// 	}
	// }

	///////////////////////////////////////////////////////
	//////////////////// INPUT VALIDATION /////////////////
	///////////////////////////////////////////////////////

	//Initialize variable to keep track of any errors
	$anyErrors= FALSE;

	
	// Check date validity
	// $isValidDate= checkdate($expenseMonth, $expenseDay, $expenseYear);
	// if (!$isValidDate) {$errorMessage= "Error: Invalid Date"; $anyErrors= TRUE;}

	///////////////////////////////////////////////////////
	////////// INPUT PARSING AND WRITE TO SQL DB //////////
	///////////////////////////////////////////////////////

	// Only input information into database if there are no errors
	if ( !$anyErrors ) 
	{
		// Create a DateTime object based on inputted data
		// $startDateObj= DateTime::createFromFormat('Y-m-d', $startYear . "-" . $startMonth . "-" . $startDay);

		// // Get the name of the month (e.g. January) of this expense
		// $expenseMonthName= $startDateObj->format('F');

		// // Get the day of the week (e.g. Tuesday) of this expense
		// $expenseDayOfWeekNum= $startDateObj->format('w');
		// $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
		// $expenseDayOfWeek = $days[$expenseDayOfWeekNum];

		// Connect to Azure SQL Database
		$conn = ConnectToDabase();

		// Build SQL query to insert new expense data into SQL database
		$tsql=
		"INSERT INTO Persons (	
				StartDate,
				EndDate,
				VehicleMake,
				VehicleModel,
				EmployeeName)
		VALUES ('" . $startDate . "',
				'" . $endDate . "', 
				'" . $vehicleMake . "', 
				'" . $vehicleModel . "', 
				'" . $employeeName . "')";

		// Run query
		$sqlQueryStatus= sqlsrv_query($conn, $tsql);

		// Close SQL database connection
		sqlsrv_close ($conn);
	}

	// Initialize an array of previously-posted info
	$prevSelections = array();

	// Populate array with key-value pairs
	$prevSelections['errorMessage']= $errorMessage;
	$prevSelections['prevStartDate']= $startDate;
	$prevSelections['prevEndDate']= $endDate;
	$prevSelections['prevVehicleMake']= $vehicleMake;
	$prevSelections['prevVehicleModel']= $vehicleModel;
	$prevSelections['prevEmployeeName']= $employeeName;


	// Store previously-selected data as part of info to carry over after URL redirection
	$_SESSION['prevSelections'] = $prevSelections;

	/* Redirect browser to home page */
	header("Location: /"); 
?>