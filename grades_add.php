<?php 
session_start();
include('config.php'); 
$email = $_SESSION['email'];
$password = $_SESSION['password'];

$sql = "SELECT * FROM users WHERE email='$email' && password='$password' && type='User'";
$re = mysqli_query($con,$sql);
$number = mysqli_num_rows($re);

if($number!=1)
{
  header('location:index.php');
}


$Test_Name = $_POST['Test_Name'];
$Sub_Name = $_POST['Sub_Name'];
$Max_Marks = $_POST['Max_Marks'];
$Class_Section = $_POST['Class_Section'];
$teacher_id =  $_POST['teacher_id'];

$grade = $_POST['grade'];
$Student = $_POST['Student'];
$Roll_No = $_POST['Roll_No'];

foreach ($grade as $key => $value) {

		$get_student_name = $Student[$key];
		$get_grade = $grade[$key];
		$get_roll_no = $Roll_No[$key];

		if($get_grade=="")
		{
			$query_for_marks1 = "INSERT INTO
		 exam_marks(Test_Name,Sub_Name,Student_Name,Roll_No,Max_Marks,Class_Section,teacher_id)
			VALUES('$Test_Name','$Sub_Name','$get_student_name','$get_roll_no','$Max_Marks','$Class_Section','$teacher_id')";                          
			$run = mysqli_query($con,$query_for_marks1);
		}
		else
		{
			$query_for_marks = "INSERT INTO
		 exam_marks(Test_Name,Sub_Name,Student_Name,Roll_No,Max_Marks,Grade,Class_Section,teacher_id)
			 VALUES('$Test_Name','$Sub_Name','$get_student_name','$get_roll_no','$Max_Marks','$get_grade','$Class_Section','$teacher_id')";                          
			$run = mysqli_query($con,$query_for_marks);
		}

		
}
			if($run)
			{
				header('location:Home.php');
			}


?>