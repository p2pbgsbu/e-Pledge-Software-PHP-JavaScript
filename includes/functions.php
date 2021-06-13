<?php


// Database Connect Function
function connect(){

	$db = mysqli_connect('localhost', 'root' ,'', 'pledge');
	return $db;

}

// Function to Filter Data.

function filter_mydata($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


?>