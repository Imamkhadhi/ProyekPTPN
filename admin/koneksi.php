<?php
$servername = "localhost"; 
$username = "root";         
$password = "";             
$dbname = "db_ptpn";    


$conn = mysqli_connect($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  // Output error message if connection fails
} 

// echo "Connected successfully";  // Output success message if connection is successful


// $conn->close();


