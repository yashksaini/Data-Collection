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


$input = filter_input_array(INPUT_POST);

$password = $input["password"];
$id = $input["ID"];

$sql3 = "SELECT * FROM users WHERE ID='$id'";
$res3 = mysqli_query($con,$sql3);
$show = $res3->fetch_assoc();

$teacher_id = $show["teacher_id"];
$Teacher_Name = $input["Teacher_Name"];
$email = $input["email"];

if($input["action"]==='edit')
{

	$sql1="UPDATE teachers_detail
		   SET Teacher_Name='$Teacher_Name',email='$email'
		   WHERE Teacher_ID='$teacher_id'";
	mysqli_query($con,$sql1);

	$sql = "UPDATE users 
			SET  password='$password',email='$email'
			WHERE ID='$id'";
	mysqli_query($con,$sql);


}
if($input["action"]=='delete')
{
	$sql = "DELETE FROM users WHERE ID='$id'";
	mysqli_query($con,$sql);
}
echo json_encode($input);

?>