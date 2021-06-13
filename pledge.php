<?php


include('includes/functions.php');
include('includes/mailfunction.php');

if(!isset($_POST['name']))
{
	//
	header('location: index.php');
	exit();
}

if(!isset($_POST['email']))
{
	//
	header('location: index.php');
	exit();
}

if (empty($_POST['name']) || empty($_POST['email'])) {
  header('location: index.php');
	exit();
  
}

// Declare form variables and assign
$PersonName = filter_mydata($_POST['name']);

$PersonEmail = filter_mydata($_POST['email']);
// Lowercase email
$PersonEmail = strtolower($PersonEmail);
// Captalise Name
$PersonName = ucwords($PersonName);
// getdatestring
$dateString = date("jS \ F Y");

$certificateuid = md5($PersonName . $PersonEmail);



// Check If Email has been already used to create the certificate
//if yes.. Fetch the Unique ID and Redirect to view page


$dbx = connect();

$CheckQuery = $dbx->prepare("SELECT cert FROM pleadge_submission WHERE s_email = ?");

$CheckQuery->bind_param("s", $PersonEmail);

$CheckQuery->execute();

$CheckQuery->bind_result($cert);

$CheckQuery->store_result();

$CheckQuery->fetch();

if ($CheckQuery->num_rows > 0) {

	header('location: view.php?view='.$cert.'');
	exit();
}

$dbx->close();



// Insert the Data Into Database

$dbx = connect();

$Insert = $dbx->prepare("INSERT INTO pleadge_submission (s_email, s_name, date_time, cert) VALUES (?, ?, ?, ?)");


$Insert->bind_param("ssss", $PersonEmail, $PersonName, $dateString, $certificateuid);

sendMail($PersonEmail,$PersonName,$certificateuid);

if ($Insert->execute()) {
    
  
	header('location: view.php?view='.$certificateuid.'');
	exit();
	

}else{

}

$dbx->close();

?>