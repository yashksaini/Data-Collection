<?php
session_start();
include('config.php'); 
$email = $_SESSION['email'];
$password = $_SESSION['password'];
/* Verfying the user */

$sql = "SELECT * FROM users WHERE email='$email' && password='$password' && type='Admin'";
$re = mysqli_query($con,$sql);
$number = mysqli_num_rows($re);

if($number!=1)
{
  header('location:index.php');
}
/* Veryfying Ends */
$table_name = $_POST["table_name"];
$output = "";
$table = $table_name; // Enter Your Table Name 
$sql = mysqli_query($con,"select * from $table");
$columns_total = mysqli_num_fields($sql);

// Get The Field Name
for ($i = 0; $i < $columns_total; $i++) {
	$fieldinfo = $sql -> fetch_field();
	
$heading = $fieldinfo -> name;
$output .= '"'.$heading.'",';
}
$output .="\n";

// Get Records from the table

while ($row = mysqli_fetch_array($sql)) {
for ($i = 0; $i < $columns_total; $i++) {
$output .='"'.$row["$i"].'",';
}
$output .="\n";
}

// Download the file

$filename = $table_name.".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;

?>