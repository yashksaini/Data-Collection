<?php
session_start();
include('config.php'); 
$email = $_SESSION['email'];
$password = $_SESSION['password'];

/* Verfying the user */

$sql = "SELECT * FROM users WHERE email='$email' && password='$password' && type='User'";
$re = mysqli_query($con,$sql);
$number = mysqli_num_rows($re);

if($number!=1)
{
  header('location:index.php');
}
/* Veryfying Ends */


$input = filter_input_array(INPUT_POST);

$Grade = $input["Grade"];
$id = $input["ID"];

if($input["action"]==='edit')
{

	$sql1="UPDATE exam_marks
		   SET Grade='$Grade'
		   WHERE ID='$id'";
	mysqli_query($con,$sql1);
}
echo json_encode($input);

?>